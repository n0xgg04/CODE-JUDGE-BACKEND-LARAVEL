<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use App\Models\Problems;
use GuzzleHttp\RequestOptions;
use Tymon\JWTAuth\Facades\JWTAuth;
use GuzzleHttp\Exception\RequestException;
use Tymon\JWTAuth\Token;
use App\Models\Account;
use App\Models\Submissions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CodeRunnerController extends Controller
{
    public function judgeSubmission(Request $request, $problemId)
    {
        $code = $request->get('source_code');
        $lang = $request->get('lang');
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['result' => 'failed', 'description' => 'unauthorization'], 401);
        }
        $jwtToken = new Token(substr($token, 7));
        $decoded_token = JWTAuth::decode($jwtToken);
        // $validator = JWTAuth::getJWTProvider()->getValidator();
        //// $validator->check($decoded_token);
        $user_id = $request->get('accessToken');
        if ($code == null) return response()->json(['result' => 'failed', 'message' => 'no_code'], 400);
        if ($lang == null) return response()->json(['result' => 'failed', 'message' => 'no_lang'], 400);
        if ($user_id == null) return response()->json(['result' => 'failed', 'message' => 'unauthorize'], 401);
        $API_LINK = env('JUDGE_API_LINK');
        // Storage::makeDirectory('anhkyy');
        $problemData = Problems::select('runtime', 'memory', 'max_point')->where('problem_id', $problemId)->first();
        $userData = Account::select('uid')->where('access_token', $user_id)->first();
        if (!$userData) return response()->json(['result' => 'failed', 'message' => 'unauthorize'], 401);
        if (!Storage::exists('judge/tests/' . $problemId)) {
            return response()->json(['result' => 'error', 'message' => 'problem_not_found'], 404);
        }
        $submissionData = Submissions::create([
            'problem_id' => $problemId,
            'from_user' => $user_id,
            'language' => $lang,
            'point' => 0,
            'memory' => 0,
            'time' => 0,
            'status' => 'wt',
            'create_at' => now()
        ]);

        $submission = $submissionData['submission_id'];

        $inputFiles = Storage::allFiles('judge/tests/' . $problemId . '/input/');
        // $inputFiles =  glob(storage_path('judge/tests/' . $problemId . '/input/*'));
        $token = array();
        $AcceptedTest = 0;
        $averageMemory = 0;
        $averageTime = 0;
        $TLETest = 0;
        $mmo=0;
        $tto = 0;
        $testAm = count($inputFiles);
        foreach ($inputFiles as $oneFile) {
            $filename = basename($oneFile);
            $input = Storage::get('judge/tests/' . $problemId . '/input/' . basename($oneFile));
            $output = Storage::get('judge/tests/' . $problemId . '/output/' . basename($oneFile));
            $client = new Client();
            try {
                $response = $client->post($API_LINK . '/submissions/?wait=true', [
                    RequestOptions::TIMEOUT => (float)$problemData->runtime + 10,
                    'form_params' => [
                        'source_code' => $code,
                        'language_id' => 54,
                        "stdin" => $input,
                        "expected_output" => $output,
                        "timelimit" => $problemData->runtime,
                        // "cpu_time_limit" => 2,
                        // "cpu_extra_time" => 0.5,
                        "wall_time_limit" => 5,
                        "memory_limit" => $problemData->memory,
                        "stack_limit" => 64000,
                        "max_processes_and_or_threads" => 30,
                        "enable_per_process_and_thread_time_limit" => false,
                        "enable_per_process_and_thread_memory_limit" => true,
                        "callback_url" => "http://localhost/api/done"
                    ]
                ]);
                $response = (string) $response->getBody();
                $result = json_decode($response, true);
                $token[] = $result['token'];
                if (!isset($result['status'])) {
                    Submissions::where('submission_id', $submission)->update([
                        'status' => "rte"
                    ]);
                    return response()->json(['result' => 'success', 'AcceptedTest' => '0', 'TLETest' => '0', 'allTestAmount' => $testAm, 'description' => "rte", "you" => $user_id, "submissionId" => $submission]);
                }
                if ($result['status']['id'] == 6) {
                    Submissions::where('submission_id', $submission)->update([
                        'status' => "ce"
                    ]);
                    return response()->json(['result' => 'success', 'AcceptedTest' => 0, 'TLETest' => 0, 'allTestAmount' => $testAm, 'description' => 'ce', 'token' => $token, "you" => $user_id, "submissionId" => $submission]);
                } else if ($result['status']['id'] == 3) $AcceptedTest++;
                else if ($result['status']['id'] == 11) $TLETest++;
                $averageMemory += ($result['memory']==null)?0:$result['memory'];
                $averageTime +=  ($result['time']==null)?0:$result['time'];
                if($result['memory']!=null) $mmo++;
                if($result['time']!=null) $tto++;
            } catch (RequestException $e) {
                $TLETest++;
                continue;
            }
        }
        if ($AcceptedTest == $testAm) $des = "ac"; else
        if($AcceptedTest==0) $des="wa";
        else $des = "pa";
        Submissions::where('submission_id', $submission)->update([
            'point' => $problemData['max_point'] * $AcceptedTest / $testAm,
            'memory' => (string) round(($averageMemory/$mmo)/1024,0)." KB",
            'time' => round($averageTime/$tto,3),
            'status' => $des
        ]);
        return response()->json(['result' => 'success', 'AcceptedTest' => $AcceptedTest, 'TLETest' => $TLETest, 'allTestAmount' => $testAm, 'description' => $des, 'token' => $token, "you" => $user_id, "submissionId" => $submission]);
    }
}

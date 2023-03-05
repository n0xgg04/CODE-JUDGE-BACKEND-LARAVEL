<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contest;
use App\Models\ContestList;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class getProblemInfo extends Controller
{
    public function getAll($pid)
    {
        $results = DB::table('problems')->join('accounts', 'problems.author_id', '=', 'accounts.uid')
            ->select('problems.title', 'problems.description','problems.level','problems.test','problems.memory','problems.runtime', DB::raw('problems.max_point AS maxPoints, problems.created_at AS createAt, problems.test_description AS testDescription, problems.language_acception AS LanguageAcception, accounts.fullname AS authorName'))
            ->Where('problems.problem_id', '=', $pid)->limit(1)->get();
        if(count($results)===0) return response()->json(['result' => 'error', 'message' => 'Problem_not_found'], 404);
        $results[0]->testDescription = json_decode($results[0]->testDescription,true);
        $results[0]->test = json_decode($results[0]->test,true);
        return response()->json(['result' => 'success', 'data' => $results], 200);
    }
}

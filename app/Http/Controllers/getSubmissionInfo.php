<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contest;
use App\Models\ContestList;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class getSubmissionInfo extends Controller
{
    public function getAll($userId)
    {
        $results = DB::table('submissions')->join('problems', 'problems.problem_id', '=', 'submissions.problem_id')
            ->select('submissions.submission_id AS submissionId', 'submissions.point AS scores','submissions.memory','submissions.time AS runtime','submissions.status as submissionStatus', DB::raw('problems.title AS challengeName, submissions.created_at AS submitAt'))
            ->Where('submissions.from_user', '=', $userId)->orderBy('submissions.created_at','desc')->get();
        return response()->json(['result' => 'success', 'data' => $results], 200);
    }
}

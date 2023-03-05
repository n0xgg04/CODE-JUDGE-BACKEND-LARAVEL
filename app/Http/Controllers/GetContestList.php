<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contest;
use App\Models\ContestList;
use Illuminate\Support\Facades\DB;

class GetContestList extends Controller
{
    public function getAll(){
        $results = DB::table('contest_list')
        ->join('contests', 'contest_list.contest_id', '=', 'contests.contest_id')
        ->select('contest_list.contest_id', 'contest_list.name','contest_list.type','contest_list.language','contests.cover', 'contests.author',DB::raw('(CASE WHEN contests.password IS NULL THEN 0 ELSE 1 END) AS islocked, contest_list.start_at AS startAt,JSON_LENGTH(contests.joiner) AS joiner'))
        ->orderBy('contest_list.start_at','desc')->get();
        
        return response()->json(['result'=>'success','data' => $results],200);
    }
}

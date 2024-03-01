<?php

namespace App\Http\Controllers;

use App\Models\Authentications;
use App\Models\Candidates;
use App\Models\Votes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VoteController extends Controller
{
    public function Voting(Request $request){
        $validate = Validator::make($request->all(),[
            'candidatesId' => 'required'
        ],[
            'required' => ':attribute field is required'
        ]);

        if($validate->fails()){
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validate->errors()
            ],400);
        }else{
            $candidate = Candidates::find($request->candidatesId);
            $users = Authentications::where('token',$request->bearerToken())->first();
            if($candidate !== null){
                $vote = Votes::where('users_id', $users->users_id)->first();
                if($vote !== null){
                    return response()->json([
                        'message' => 'Anda sudah vote!'
                    ],400);
                }else{
                    $vote = Votes::create([
                        'candidates_id' => $candidate->id,
                        'users_id' => $users->users_id
                    ]);
                    return response()->json([
                        'message' => 'Vote berhasil disimpan'
                    ],201);
                }
            }else{
                return response()->json([
                    'message' => 'Candidate tidak ditemukan'
                ],404);
            }
        }
    }

    public function resultVote(){
        $vote_count = DB::table('votes')->groupBy('candidates_id')->select('candidates_id',DB::raw('COUNT(candidates_id) as vote_count'))->get('vote_count');
       if(count($vote_count) > 0){
        return response([
            'message' => 'fetch data sukses',
            'data' => [
                'vote_result' => $vote_count
            ]
        ],200);
       }else{
        return response([
            'message' => 'fetch data gagal',
            'data' => [
                'vote_result' => $vote_count
            ]
        ],400);
       }
    }
}

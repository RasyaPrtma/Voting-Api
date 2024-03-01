<?php

namespace App\Http\Controllers;

use App\Models\Candidates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CandidatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidate = Candidates::all('id','nama','thumbnail','no_urut');

       if($candidate !== null){
        return response()->json([
            'message' => 'fetch data sukses',
            'data' => [
                'candidates' => $candidate
            ]
        ],200);
       }else{
        return response()->json([
            'message' => 'fetch data gagal',
            'data' => [
                'candidates' => $candidate
            ]
        ],404);
       }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'name' => 'required|min:5|max:100',
            'thumbnail' => 'required',
            'no_urut' => 'required|unique:candidates,no_urut|numeric'
        ],[
            'required' => ':attribute harus terisi',
            'min' => ':attribute minimal 5 karakter',
            'max' => ':attribute maximal 100 karakter',
            'numeric' => 'format :attribute harus angka',
            'unique' => ':attribute harus unik'
        ]);

        if($validate->fails()){
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validate->errors()
            ],400);
        }else{
            $candidate = Candidates::create([
                'nama' => $request->name,
                'thumbnail' => $request->thumbnail,
                'no_urut' => $request->no_urut
            ]);
            return response()->json([
                'message' => 'candidate berhasil ditambahkan',
                'data' => [
                    'candidate' =>[
                        'id' => $candidate->id,
                        'nama' => $candidate->nama,
                        'thumbnail' => $candidate->thumbnail,
                        'no_urut' => $candidate->no_urut
                    ]
                ]
            ],201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $candidate = Candidates::find($id);

        if($candidate !== null){
            $candidate->update([
                'nama' => $request->name,
                'thumbnail' => $request->thumbnail,
                'no_urut' => $request->no_urut
            ]);
            return response()->json([
                'message' => 'candidate berhasil diupdate',
                'data' => [
                    'candidates' => [
                        'id' => $candidate->id,
                        'nama' => $candidate->nama,
                        'thumbnail' => $candidate->thumbnail,
                        'no_urut' => $candidate->no_urut
                    ]
                ]
            ],200   );
        }else{
            return response()->json([
                'message' => 'candidate tidak ditemukan!'
            ],404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $candidate = Candidates::find($id);

        if($candidate !== null){
            $candidate->delete();
            return response()->json([
                'message' => 'candidate berhasil dihapus'
            ],200);
        }else{
            return response()->json([
                'message' => 'candidate tidak ditemukan'
            ],404);
        }
    }
}

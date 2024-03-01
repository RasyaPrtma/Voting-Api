<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all('id', 'name', 'email', 'password');

        if ($users !== null) {
            return response()->json([
                'message' => 'fetch users sukses',
                'data' =>[
                    'users' => $users
                ]
            ]);
        } else {
            return response()->json([
                'message' => 'users tidak ada',
                'data' => [
                    'users' => $users
                ]
            ], 404);
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
        $validate = Validator::make($request->all(), [
            'name' => 'required|unique:users,name',
            'email' => 'required|unique:users,email|email:rfc',
            'password' => 'required'
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute harus unik',
            'email' => ':attribute harus email yang valid'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validate->errors()
            ], 400);
        } else {
            $users = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            return response()->json([
                'message' => 'users berhasil ditambahkan',
                'data' => [
                    'users' => [
                        'id' => $users->id,
                        'name' => $users->name,
                        'email' => $users->email,
                        'password' => $users->password
                    ]
                ]

            ], 200);
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
        $users = User::find($id);
        if($users !== null){
            $users->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
        }else{
            return response()->json([
                'message' => 'users tidak ditemukan!'
            ],404); 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $users = User::find($id);
        if($users !== null){
            $users->delete();
            return response()->json([
                'message' => 'users berhasil di delete',
            ],200);
        }else{
            return response()->json([
                'message' => 'users tidak ditemukan!'
            ],404);
        }
    }
}

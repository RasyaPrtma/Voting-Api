<?php

namespace App\Http\Controllers;

use App\Models\Authentications;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function Login(Request $request){
        $validate = Validator::make($request->all(),[
            'email' => 'required|email:rfc',
            'password' => 'required|min:5'
        ],[
            'required' => ':attribute harus terisi',
            'email' => 'format :attribute harus valid',
            'min' => ':attribute minimal 5 karakter'
        ]);

        if($validate->fails()){
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validate->errors() 
            ],400);
        }else{
            $login = User::where('email',$request->email)->first();
            if($login !== null && Hash::check($request->password,$login->password)){
                $token = Authentications::create([
                    'token' => md5($login->id),
                    'users_id' => $login->id
                ]);
                return response()->json([
                    'message' => 'Authentikasi berhasil',
                    'data' => [
                        'name' => $login->name,
                        'email' => $login->email,
                        'accesToken' => $token->token
                    ]
                ],200);
            }else{
                return response()->json([
                    'message' => 'Unauthorized'
                ],401);
            }
        }
    }

    public function Logout(Request $request){
        $token = Authentications::where('token',$request->bearerToken())->first();
        if($token !== null){
            $token->delete();
            return response()->json(['message' => 'logout berhasil'],200);
        }else{
            return response()->json([
                'message' => 'Unauthorized'
            ],401);
        }
    }
}

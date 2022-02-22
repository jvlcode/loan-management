<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),
            [
                'first_name'=>'required',
                'middle_name'=>'required',
                'last_name'=>'required',
                'email'=>'required|email|unique:users',
                'password'=>'required',
                'c_password'=>'required|same:password',
                'phone'=>'required',
                'address'=>'required'
            ]
            );

        if ($validator->fails()) {
            return response()->json(['message'=>'validation error'],400);
        }

        $data = $validator->validated();
        //removing confirmation password for insert
        unset($data['c_password']);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        //returning bearer token
        return $this->userToken($user);

    }


    public function login(Request $request){
        if (Auth::attempt(['email'=>$request->input('email'),'password'=>$request->input('password')] )) {
           $user = Auth::user();
           //returning bearer token
           return $this->userToken($user);
        }else{
            return response()->json(['message'=>'invalid credentials error'],401);
        }
    }

    public function userToken($user){
        $response['token'] = $user->createToken('Myapp')->plainTextToken;
        $response['name'] = $user->fullname;
        return response()->json($response,200);
    }

}

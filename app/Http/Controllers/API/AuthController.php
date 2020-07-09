<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Admin;
use App\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = Customer::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response([ 'user' => $user, 'access_token' => $accessToken]);
    }

    public function login(Request $request)
    {

        $user = Customer::where("email", request('email'))->first();
        if(!isset($user)){
            return "Admin Not found";
        }

        if (!Hash::check(request('password'), $user->password)) {
            return "Incorrect password";
        }

        $tokenResult = $user->createToken('authToken');
        $user->access_token = $tokenResult->accessToken;
        $user->token_type = 'Bearer';
        return $user;

        // $loginData = $request->validate([
        //     'email' => 'email|required',
        //     'password' => 'required'
        // ]);

        // if (!auth('admins')->attempt($loginData)) {
        //     return response(['message' => 'Invalid Credentials']);
        // }

        // $user = auth('admins')->user();
        // $success['token'] =  $user->createToken('authToken')->accessToken;

        // $accessToken = auth()->user()->createToken('authToken')->accessToken;

        // return response(['user' => $user, 'access_token' => $success]);

    }
}

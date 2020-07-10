<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Admin;
use App\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

        // if(auth('customers')->attempt(['email' => request('email'), 'password' => request('password')])){
        //     $user = Auth::guard('customers')->user();
        //     $success['token'] =  $user->createToken('authToken')->accessToken;
        //     return response()->json(['success' => $success], $this->successStatus);
        // }
        // else{
        //     return response()->json(['error'=>'Unauthorised'], 401);
        // }

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

    public function logout()
    {
        if (Auth::guard('customers')->user()) {
            // $user = Auth::user()->token();
            // $user->revoke();

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Logout successfully'
            // ]);

            $user = Auth::guard('customers')->user();
            return response()->json(['success' => $user]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Unable to Logout'
            ]);
        }
     }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Localadmin;
use App\Models\Student;
use App\Models\School;
use Illuminate\Http\Request;
use Illumuniate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //login function
    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // check email
        $user = User::where('email', $fields['email'])->first();

        // check password
        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'Bad credentials'
            ], 401);

        // check if user is authenticated by admin
        }else if($user->account_status == '0'){
            return response([
                'message' => 'Account is not active'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    //logout function
    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}

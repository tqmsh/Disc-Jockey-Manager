<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illumuniate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //register function
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed', 
            'role' => 'required|numeric'
        ]);

        if ($fields['role'] == '3'){
            $fields = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|confirmed', 
                'role' => 'required|numeric',
                'school_id' => 'required|numeric',
                'school' => 'required|string', 
                'phonenumber' => 'required|string',
            ]);

            $user = User::create([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'role' => $fields['role'],
                'password' => bcrypt($fields['password'])
            ]);

            $student = Student::create([
                'name' => $fields['name'],
                'user_id' => $user->id,
                'email' => $fields['email'],
                'password' => bcrypt($fields['password']),
                'role' => $fields['role'],
                'school_id' => $fields['school_id'],
                'school' => $fields['school'],
                'phonenumber' => $fields['phonenumber']
            ]);
        }
        

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
    
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

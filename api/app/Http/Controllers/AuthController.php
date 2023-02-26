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
    //register function
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed', 
            'role' => 'required|numeric'
        ]);

        // Student Registration
        if ($fields['role'] == '3'){
            $fields = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|confirmed', 
                'role' => 'required|numeric',
                'phonenumber' => 'required|string',
                'school_name' => 'required|string',
                'country' => 'required|string',
                'state_province' => 'required|string',
                'county' => 'required|string',
            ]);

            $match = ['school_name' => $fields['school_name'], 'country' => $fields['country'], 'state_province' => $fields['state_province'], 'county' => $fields['county']];


             //Find the school in where the school_name, country, state_rpovince and county matches
            $school=School::where($match)->first();
            if(!$school){
                return response()->json(['message' => 'School not found'], 404);
            }else{
                $fields['school_id'] = $school->id;
            }
            
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
                'school' => $fields['school_name'],
                'phonenumber' => $fields['phonenumber']
            ]);
        }

        // Local Admin Registration
        else if($fields['role'] == '2'){
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

            $student = Localadmin::create([
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

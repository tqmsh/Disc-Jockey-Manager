<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\School;
use App\Models\Localadmin;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed', 
            'phonenumber' => 'required|string',
            'school_name' => 'required|string',
            'country' => 'required|string',
            'state_province' => 'required|string',
            'county' => 'required|string',
            'grade' => 'required|string',
            'allergies' => 'required|string',
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
            'firstname' => $fields['firstname'],
            'lastname' => $fields['lastname'],
            'role' => '3',
            'phonenumber' => $fields['phonenumber'],
            'password' => bcrypt($fields['password']),
            'country' => $fields['country'],
        ]);

        $student = Student::create([
            'firstname' => $fields['firstname'],
            'lastname' => $fields['lastname'],
            'grade' => $fields['grade'],
            'user_id' => $user->id,
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'school_id' => $fields['school_id'],
            'school' => $fields['school_name'],
            'phonenumber' => $fields['phonenumber'],
            'allergies' => $fields['allergies']
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
}

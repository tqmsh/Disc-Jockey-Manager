<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\School;
use App\Models\Localadmin;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Update the student profile
        $user = $request->user();
        $student = Student::find($id);

        // If student not found
        if($student == null){
            return response()->json(['message' => 'Student not found'], 404);
        }

        // If student is not the same as the user
        else if($user->role == 3){
            if($user->student_id != $student->id){
                return response()->json(['message' => 'You are not authorized to update another student'], 401);
            }else{
                $student->update($request->all());
                return $student; 
            }
        }

        // If user is a local admin and the student is in the same school
        else if($user->role == 2){

            // Check if the local admin is in the same school as the student
            $localadmin = LocalAdmin::where('user_id', $user->id)->first();
            if($localadmin->school_id != $student->school_id){
                return response()->json(['message' => 'You are not authorized to update this student'], 401);
            }else{
                $student->update($request->all());
            return $student;
            }
        }

        // If user is a super admin
        else if($user->role == 1){
            $student->update($request->all());
            return $student; 
        }

        // All other roles
        else{
            return response()->json(['message' => 'You are not authorized to update this student'], 401);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function register(Request $request){
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

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
}
    
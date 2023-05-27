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
        // If user is a local admin and the student is in the same school
        else if($user->role == 2){

            // Check if the local admin is in the same school as the student
            $localadmin = LocalAdmin::where('user_id', $user->id)->first();
            if($localadmin->school_id != $student->school_id){
                return response()->json(['message' => 'You are not authorized to update this student'], 401);
            }else{
                // Update only allergies, firstname and lastname
                $student->update($request->only('allergies', 'first_name', 'last_name'));
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

    public function updateStudent(Request $request){
        
        $user = $request->user();

        // Find if user is a student
        $student = Student::where('user_id', $user->id)->first();
        if(!$student){
            return response()->json(['message' => 'You are not a student'], 401);
        }

        $student->update($request->only('firstname', 'lastname', 'allergies'));
        $user->update($request->only('firstname', 'lastname'));
        //Successfully updated student

        // return student and user
        return response()->json(['student' => $student, 'user' => $user], 200);
    }
}
    
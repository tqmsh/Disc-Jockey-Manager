<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //index function returns first and last name of all users 
    //!THIS IS JUST A TEST
    public function index()
    {
        $users = User::all();
        $userNames = [];
        foreach ($users as $user) {
            $userNames[] = [
                'first_name' => $user->firstname,
                'last_name' => $user->last_name
            ];
        }
        return $userNames;
    }
}

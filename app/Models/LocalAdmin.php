<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Localadmin extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = ['user_id', 'firstname', 'lastname', 'phonenumber', 'email', 'school'];

    public function scopeFilter($query, array $filters){

        $query  ->join('users', 'users.id', '=', 'localadmins.user_id')
                ->join('schools', 'school_name', '=', 'school');


        if(isset($filters['school'])){
            $query ->where('school', 'like', '%' . request('school') . '%');
        }

        if(isset($filters['country'])){
            $query->where('users.country', 'like', '%' . request('country') . '%');
        }

        if(isset($filters['school_board'])){
            $query->where('school_board', 'like', '%' . request('school_board') . '%');
        }

        if(isset($filters['state_province'])){
            $query->where('state_province', 'like', '%' . request('state_province') . '%');
        }

        $query->select('localadmins.*');
    }
    
    public function getSchool($school){
        return School::where('school_name', $school)->get();
    }

    
    public function getUser($email){
        return User::where('email', $email)->get();
    }
}

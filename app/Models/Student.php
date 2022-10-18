<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Platform\Models\User;
use Orchid\Screen\AsSource;


class Student extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = ['user_id', 'firstname', 'lastname', 'grade', 'phonenumber', 'email', 'ticketstatus', 'table_id', 'school', 'event_id', 'allergies'];

    //relatioships
    public function getCountry($user_id){
        return User::find($user_id)->country;
    }

    
    public function getUser($user_id){
        return User::find($user_id);
    }
}

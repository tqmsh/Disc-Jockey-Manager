<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Orchid\Screen\AsSource;


class Student extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = ['user_id', 'firstname', 'lastname', 'grade', 'phonenumber', 'email', 'ticketstatus', 'table_id', 'school', 'event_id', 'allergies'];
    
    public function scopeFilter($query, array $filters){

        if($filters['country'] ?? false){
            $query ->join('users', 'students.user_id', '=', 'users.id')
                    ->where('country', 'like', '%' . request('country') . '%');
        }
    }

    public function getCountry($email){
        return User::where('email', $email)->get('country')->value('country');
    }

    
    public function getUser($user_id){
        return User::find($user_id);
    }
}

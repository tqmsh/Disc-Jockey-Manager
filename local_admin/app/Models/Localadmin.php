<?php

namespace App\Models;

use Exception;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Localadmin extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = ['user_id', 'account_status', 'school_id', 'firstname', 'created_at', 'updated_at', 'lastname', 'phonenumber', 'email', 'school'];

    public function scopeFilter($query, array $filters){

        try{

            $query->join('users', 'users.id', '=', 'localadmins.user_id')
                    ->join('schools', 'schools.id', '=', 'localadmins.school_id');

            if(isset($filters['school'])){
                $query ->where('school', 'like', '%' . request('school') . '%');
            }

            if(isset($filters['country'])){
                $query->where('country', 'like', '%' . request('country') . '%');
            }

            if(isset($filters['school_board'])){
                $query->where('school_board', 'like', '%' . request('school_board') . '%');
            }

            if(isset($filters['state_province'])){
                $query->where('state_province', 'like', '%' . request('state_province') . '%');
            }

            $query->select('localadmins.*');


        }catch(Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e);
        }
    }
}

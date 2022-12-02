<?php

namespace App\Models;

use Exception;
use App\Models\Localadmin;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Student extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = ['user_id', 'school_id', 'created_at', 'updated_at', 'firstname', 'lastname', 'grade', 'phonenumber', 'email', 'ticketstatus', 'table_id', 'school', 'event_id', 'allergies'];
    
    public function scopeFilter($query, array $filters){
        
        try{

            if(isset($filters['sort_option'])){
                $query->orderBy($filters['sort_option'], 'asc');
            }

            if(isset($filters['event_name'])){
                $query->where('event_id', $filters['event_name']);
            }

            if(isset($filters['ticketstatus'])){
                $query->where('ticketstatus', $filters['ticketstatus']);
            }


            $query->select('students.*');

        }catch(Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e);
        }

    }
}

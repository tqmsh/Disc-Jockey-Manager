<?php

namespace App\Models;

use Exception;
use App\Models\User;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Student extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'user_id',
        'school_id',
        'account_status',
        'created_at',
        'updated_at',
        'firstname',
        'lastname',
        'grade',
        'phonenumber',
        'email',
        'school',
        'allergies'
    ];
    
    public function scopeFilter($query, array $filters){

        try{
            
            $query  ->join('users', 'users.id', '=', 'students.user_id')
                    ->join('schools', 'schools.id', '=', 'students.school_id');

            if(isset($filters['sort_option'])){
                $query->orderByRaw($filters['sort_option'] . ' ASC');
            }       

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


            if(isset($filters['event_id']) || isset($filters['ticketstatus'])){
                $query->join('event_attendees', 'students.user_id', '=', 'event_attendees.user_id');
                (isset($filters['event_id'])) ? $query->where('event_attendees.event_id', $filters['event_id']) : null;
                (isset($filters['ticketstatus'])) ? $query->where('event_attendees.ticketstatus', $filters['ticketstatus']) : null;
            }

            $query->select('students.*');

        }catch(Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e);
        }

    }

    //create relationship with student bids
    public function bids(){
        return $this->hasMany(StudentBids::class, 'student_id');
    }
}

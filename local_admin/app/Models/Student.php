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
        'allergies',
    ];
    
    public function scopeFilter($query, array $filters){
        try{

            if(isset($filters['sort_option'])){
                $query->orderByRaw($filters['sort_option'] . ' ASC');
            }

            if(!empty($filters['name'])){
                $query->where('firstname', 'like', '%' . $filters['name'] . '%')
                    ->orWhere('lastname', 'like', '%' . $filters['name'] . '%');
            }

            if(!empty($filters['email'])){
                $query->where('email', 'like', '%' . $filters['email'] . '%');
            }

            if(!empty($filters['grade'])){
                $query->where('grade', 'like', '%' . $filters['grade'] . '%');
            }
            
            if(isset($filters['event_id']) || isset($filters['ticketstatus'])){
                $query->join('event_attendees', 'students.user_id', '=', 'event_attendees.user_id');
                (isset($filters['event_id'])) ? $query->where('event_attendees.event_id', $filters['event_id']) : null;
                (isset($filters['ticketstatus'])) ? $query->where('event_attendees.ticketstatus', $filters['ticketstatus']) : null;
            }

            $query->select('students.*')->get();

        }catch(Exception $e){

            Alert::error('There was an error processing the scope filter. Error Message: ' . $e->getMessage());
        }

    }

    //create relationship with student bids
    public function bids(){
        return $this->hasMany(StudentBids::class, 'student_id');
    }
}

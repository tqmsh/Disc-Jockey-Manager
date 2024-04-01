<?php

namespace App\Models;

use Exception;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Events extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = ['event_name', 'updated_at', 'created_at', 'school_id', 'event_creator', 'event_start_time', 'event_info', 'event_address', 'event_finish_time', 'school', 'event_zip_postal', 'event_rules', 'ticket_price', 'capacity'];


    public function scopeFilter($query, array $filters){

        try{

            $query->join('schools', 'schools.id', '=', 'school_id');


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

            $query->select('events.*');


        }catch(Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e->getMessage());
        }
    }

    public function getFullAttribute(): string
    {
        $school = School::find($this->attributes['school_id']);
        return $this->attributes['event_name'] . ' (' . $this->attributes['school'] . ' | ' . $school->county . ') ';
    }

    public function creator(){
        return $this->belongsTo(User::class, 'event_creator');
    }

    public function food(){
        return $this->hasMany(Food::class, 'event_id');
    }

    public function allergies(){
        $allergies = Student::whereIn('user_id', EventAttendees::where('event_id', $this->id)->pluck('user_id'))->whereNotNull('allergies')->pluck('allergies')->toArray();

        //make the array unique and get the count of each allergy
        $allergies = array_count_values($allergies);

        //sort the array by the count of each allergy
        arsort($allergies);

        return $allergies;
    }

    public function couples(){
        return $this->hasMany(Couple::class, 'event_id', 'id');
    }

    public function couple_requests(){
        return $this->hasMany(CoupleRequest::class, 'event_id', 'id');
    }
}

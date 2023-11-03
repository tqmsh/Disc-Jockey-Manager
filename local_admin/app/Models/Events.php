<?php

namespace App\Models;

use Exception;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Events extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = ['event_name', 'venue_id', 'updated_at', 'created_at', 'school_id', 'event_creator', 'event_start_time', 'event_info', 'event_address', 'event_finish_time', 'region_id', 'school', 'event_zip_postal', 'event_rules'];


    public function scopeFilter($query, array $filters){

        try{

            $query->join('schools', 'schools.id', '=', 'school_id');

            if(isset($filters['event'])){
                $query->where('event_name', 'like', '%' . request('event') . '%');
            }

            if(isset($filters['sort_option'])){
                // Specify ASC or DESC in sort_option string
                // Otherwise, defaults to ASC
                $query->orderByRaw($filters['sort_option']);
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

            $query->select('events.*');


        }catch(Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e);
        }
    }

    public function getFullAttribute(): string
    {
        $school = School::find($this->attributes['school_id']);
        return $this->attributes['event_name'] . ' (' . $this->attributes['school'] . ' | ' . $school->county . ') ';
    }

    //create relationship with vendor bids
    public function bids(){
        return $this->hasMany(EventBids::class, 'event_id');
    }

    public function food(){
        return $this->hasMany(Food::class, 'event_id');
    }

    public function students(){
        return $this->hasMany(EventAttendees::class, 'event_id');
    }

    public function allergies(){
        $allergies = Student::whereIn('user_id', EventAttendees::where('event_id', $this->id)->pluck('user_id'))->whereNotNull('allergies')->pluck('allergies')->toArray();

        //make the array unique and get the count of each allergy
        $allergies = array_count_values($allergies);

        //sort the array by the count of each allergy
        arsort($allergies);

        return $allergies;
    }
}

<?php

namespace App\Models;

use Exception;
use App\Models\School;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Events extends Model
{
    use HasFactory;

    protected $fillable = ['event_name', 'venue_id', 'updated_at', 'created_at', 'school_id', 'event_creator', 'event_start_time', 'event_info', 'event_address', 'event_finish_time', 'school', 'event_zip_postal', 'event_rules'];


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
}

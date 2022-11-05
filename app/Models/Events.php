<?php

namespace App\Models;

use App\Models\School;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Events extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = ['event_name', 'event_creator', 'event_start_time', 'event_info', 'event_address', 'event_finish_time', 'school', 'event_zip_postal', 'event_rules'];


    public function scopeFilter($query, array $filters){

        $query  ->join('seating', 'seating.event_id', '=', 'events.id')
                ->join('schools', 'school_name', '=', 'school');


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
    }
    
    public function getSchool($school){
        return School::where('school_name', $school)->get();
    }
}

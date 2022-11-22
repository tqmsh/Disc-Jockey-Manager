<?php

namespace App\Models;

use Exception;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = ['school_name', 'county', 'country', 'state_province', 'school_board', 'address', 'zip_postal', 'phone_number', 'fax', 'metropolitan_region', 'city_municipality', 'total_students', 'school_data', 'website', 'teacher_id', 'nces_id'];

    public function scopeFilter($query, array $filters){

        try{

            if(isset($filters['school'])){
                $query ->where('school_name', 'like', '%' . request('school') . '%');
            }

            if(isset($filters['country'])){
                $query->where('country', 'like', '%' . request('country') . '%');
            }

            if(isset($filters['county'])){
                $query->where('county', 'like', '%' . request('county') . '%');
            }

            if(isset($filters['state_province'])){
                $query->where('state_province', 'like', '%' . request('state_province') . '%');
            }

            $query->select('schools.*');


        }catch(Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e);
        }
    }

    public function getFullAttribute(): string
    {
        return $this->attributes['school_name'] . ' (' . $this->attributes['county'] . ', ' . $this->attributes['state_province'] .')';
    }

}

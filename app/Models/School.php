<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class School extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = ['school_name', 'county', 'country', 'state_province', 'school_board', 'address', 'zip_postal', 'phone_number', 'fax', 'metropolitan_region', 'city_municipality', 'total_students', 'school_data', 'website', 'teacher_id', 'nces_id'];

    public function scopeFilter($query, array $filters){

        if(isset($filters['school'])){
            $query ->where('school_name', 'like', '%' . request('school') . '%');
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

        $query->select('schools.*');
    }

    public function getFullAttribute(): string
    {
        return $this->attributes['school_name'] . ' (' . $this->attributes['county'] . ', ' . $this->attributes['state_province'] .')';
    }

}

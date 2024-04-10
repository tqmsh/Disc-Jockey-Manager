<?php

namespace App\Models;

use Exception;
use App\Models\School;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Events extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = ['event_name', 'venue_id', 'updated_at', 'created_at', 'school_id', 'event_creator', 'event_start_time', 'event_info', 'event_address', 'event_finish_time', 'school', 'event_zip_postal', 'event_rules', 'ticket_price', 'capacity', 'interested_vendor_categories',];


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

    public function school_1()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }
    public function couples(){
        return $this->hasMany(Couple::class, 'event_id', 'id');
    }

    public function couple_requests(){
        return $this->hasMany(CoupleRequest::class, 'event_id', 'id');
    }

    public function interestedVendorCategories(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !is_null($value) ? json_decode($value, true) : null,
            set: fn (array|null $value) => !is_null($value) ? json_encode(array_map('intval', $value)) : null,
        );
    }

    public function getInterestedCategoriesNames(): string|null
    {
        return $this->interested_vendor_categories ? implode(', ', array_map(fn ($category_id) => Categories::find($category_id)->name, $this->interested_vendor_categories)) : null;
    }
}

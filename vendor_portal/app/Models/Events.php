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

    protected $fillable = ['event_name', 'updated_at', 'created_at', 'school_id', 'event_creator', 'event_start_time', 'event_info', 'event_address', 'event_finish_time', 'school', 'event_zip_postal', 'event_rules'];


    public function scopeFilter($query, array $filters){

        try{

            if(isset($filters['region_id'])){
                $query ->where('region_id', $filters['region_id']);
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
    
    public function interestedVendorCategories(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => json_decode($value, true),
            set: fn (array $value) => json_encode(array_map('intval', $value)),
        );
    }

    public function getInterestedCategoriesNames(): string
    {
        return implode(', ', array_map(fn ($category_id) => Categories::find($category_id)->name, $this->interested_vendor_categories));
    }
}

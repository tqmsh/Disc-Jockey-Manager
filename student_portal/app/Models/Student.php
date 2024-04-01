<?php

namespace App\Models;

use Exception;
use App\Models\Localadmin;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Student extends Model
{
    use HasFactory;
    use AsSource;

    protected $allowedFilters = [
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
        'interested_vendor_categories',
    ];
    
    public function scopeFilter($query, array $filters){
        
        try{

            if(isset($filters['sort_option'])){
                $query->orderBy($filters['sort_option'], 'asc');
            }

            if(isset($filters['event_name'])){
                $query->where('event_id', $filters['event_name']);
            }

            if(isset($filters['ticketstatus'])){
                $query->where('ticketstatus', $filters['ticketstatus']);
            }

            $query->select('students.*');

        }catch(Exception $e){

            Alert::error('There was an error processing the scope filter. Error Message: ' . $e->getMessage());
        }

    }

    public function school(){
        return $this->belongsTo(School::class, 'school_id', 'id');
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

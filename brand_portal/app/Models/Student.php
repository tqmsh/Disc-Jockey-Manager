<?php

namespace App\Models;

use Exception;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Student extends Model
{
    use HasFactory;
    use AsSource;

    // This is the array of fields that can be mass assigned.
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
        'allergies'
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

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function school(){
        return $this->belongsTo(School::class, 'school_id', 'id');
    }
}

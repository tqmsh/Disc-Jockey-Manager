<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;

class Promfluencer extends Model
{
    use HasFactory, AsSource;

    public function scopeFilter($query, array $filters){

        try{
            $query->join('students', 'students.user_id', '=', 'promfluencers.user_id');

            if(isset($filters['school'])){
                $query ->where('students.school_id', $filters['school']);
            }

            if(isset($filters['grade'])){
                $query ->where('students.grade', $filters['grade']);
            }

            $query->select('promfluencers.*');


        }catch(Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e->getMessage());
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

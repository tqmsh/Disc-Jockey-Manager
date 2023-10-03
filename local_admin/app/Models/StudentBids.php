<?php

namespace App\Models;

use Exception;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentBids extends Model
{
    use HasFactory;
    use AsSource;


       public function scopeFilter($query, array $filters){

        try{

            if(isset($filters['category_id'])){
                $query ->where('category_id', $filters['category_id']);
            }

            $query->get();

        }catch(Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e->getMessage());
        }
    }

    //relationship to user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

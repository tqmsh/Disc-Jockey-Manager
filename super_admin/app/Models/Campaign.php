<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Support\Facades\Alert;

class Campaign extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category_id',
        'region_id',
        'title',
        'image',
        'website',
        'clicks',
        'impressions',
        'gender',
        'active'
    ];

    public function scopeFilter($query, array $filters){

        try{
            if(isset($filters['title'])){
                $query ->where('title', $filters['title']);
            }

            if(isset($filters['category_id'])){
                $query ->where('category_id', $filters['category_id']);
            }
            
            if(isset($filters['region_id'])){
                $query ->where('region_id', $filters['region_id']);
            }

            $query->select('campaigns.*');


        }catch(Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e->getMessage());
        }
    }
}

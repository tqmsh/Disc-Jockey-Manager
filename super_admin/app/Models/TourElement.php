<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;

class TourElement extends Model
{
    use HasFactory;
    use AsSource;

    protected $table = 'tour_element';

    protected $fillable = [
        'id',
        'screen',
        'title',
        'element',
        'description',
        'order_element',
        'created_at',
        'updated_at'
    ];

    public function screenOwner()
    {
        return $this->belongsTo(TourScreen::class, 'screen', 'id');
    }

    public function scopeFilter($query, array $filters){

        try{

            if(isset($filters['screen'])){
                $query->where('screen', 'like', '%' . request('screen') . '%');
            }

            if(isset($filters['name_filter']) && isset($filters['search_input_by'])){
                $query->where($filters['search_input_by'], 'like', '%' . request('name_filter') . '%');
            }

            $query->select('tour_element.*');

        }catch(Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e->getMessage());
        }

    }

}

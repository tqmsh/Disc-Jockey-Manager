<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Food extends Model
{
    use HasFactory;
    use AsSource;

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if(isset($filters['vegetarian'])) {
            $query->where('vegetarian', true);
        }
        if(isset($filters['vegan'])) {
            $query->where('vegan', true);
        }
        if(isset($filters['halal'])) {
            $query->where('halal', true);
        }
        if(isset($filters['kosher'])) {
            $query->where('kosher', true);
        }
        if(isset($filters['gluten_free'])) {
            $query->where('gluten_free', true);
        }
        if(isset($filters['nut_free'])) {
            $query->where('nut_free', true);
        }
        if(isset($filters['dairy_free'])) {
            $query->where('dairy_free', true);
        }
        return $query->select('*');
    }


    public function event()
    {
        return $this->belongsTo(Events::class);
    }
}

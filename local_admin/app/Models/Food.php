<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Food extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'id',
        'event_id',
        'name',
        'description',
        'image',
        'vegetarian',
        'vegan',
        'halal',
        'kosher',
        'gluten_free',
        'nut_free',
        'dairy_free',
        'created_at',
        'updated_at',
    ];

    public function event()
    {
        return $this->belongsTo(Events::class);
    }
}

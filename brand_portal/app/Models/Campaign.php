<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Campaign extends Model
{
    use HasFactory;
    use AsSource;
    protected $fillable = [
        'user_id',
        'category_id',
        'region_id',
        'title',
        'image',
        'website',
        'clicks',
        'impressions'
    ];
}

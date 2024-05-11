<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'gender',
        'impressions'
    ];
}

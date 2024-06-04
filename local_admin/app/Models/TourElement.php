<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

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
    ];


}

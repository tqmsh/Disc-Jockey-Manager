<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class TourScreen extends Model
{
    use HasFactory;
    use AsSource;

    protected $table = 'tour_screens';

    protected $fillable = [
        'id',
        'screen',
    ];


}

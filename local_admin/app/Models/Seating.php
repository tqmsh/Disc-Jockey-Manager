<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Seating extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'event_id',
        'tablename',
        'capacity',
        'approved',
        'created_at',
        'updated_at'

    ];
}

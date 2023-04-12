<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seating extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 
        'event_id', 
        'tablename', 
        'creaeted_at',
        'updated_at'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAttendees extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'event_id', 
        'table_id', 
        'ticketstatus', 
        'created_at',
        'updated_at'
    ];
}

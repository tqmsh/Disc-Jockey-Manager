<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'song_id',
        'event_id',
        'requester_user_id',
        'requester_user_ids'
    ];
}
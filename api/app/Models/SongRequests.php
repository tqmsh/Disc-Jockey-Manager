<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongRequests extends Model
{
    use HasFactory;

    protected $fillable = [ 'id', 'song_id', 'event_id', 'user_id' ];
}

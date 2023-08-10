<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class NoPlaySong extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'song_id',
        'event_id',
    ];

    public function song()
    {
        return $this->belongsTo(Song::class, 'song_id');
    }

    public function event()
    {
        return $this->belongsTo(Events::class, 'event_id');
    }
}

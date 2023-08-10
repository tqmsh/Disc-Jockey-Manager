<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class SongRequest extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'song_id',
        'event_id',
        'user_id',
    ];

    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    public function event()
    {
        return $this->belongsTo(Events::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id');
    }
}

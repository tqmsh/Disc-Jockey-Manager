<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Song extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'title',
        'artists',
        'explicit',
        'status'
    ];

    public function getFullAttribute(): string
    {
        return $this->attributes['title'] . ' - ' . $this->attributes['artists'];
    }

    public function scopeFilter($query, $filters)
    {
        if (!empty($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if (!empty($filters['artists'])) {
            $query->where('artists', 'like', '%' . $filters['artists'] . '%');
        }

        if (isset($filters['explicit'])) {
            $filters['explicit'] = strtolower($filters['explicit']);
            if ($filters['explicit'] == 'yes') {
                $query->where('explicit', 1)->where('status', 1);
            } else if ($filters['explicit'] == 'no') {
                $query->where('explicit', 0)->where('status', 1);
            } else if ($filters['explicit'] == 'unknown') {
                $query->where('status', 0);
            }
        }

        if (isset($filters['status'])) {
            $filters['status'] = strtolower($filters['status']);
            if ($filters['status'] == 'approved' || $filters['status'] == 'pending') {
                $query->where('status', $filters['status'] == 'approved');
            }
        }

        return $query;
    }

    public function noPlaySongs()
    {
        return $this->hasMany(NoPlaySong::class, 'song_id');
    }
}

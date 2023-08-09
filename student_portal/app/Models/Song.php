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
        'explicit'
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

        if (!empty($filters['album'])) {
            $query->where('album', 'like', '%' . $filters['album'] . '%');
        }

        if (isset($filters['explicit'])) {
            $filters['explicit'] = strtolower($filters['explicit']);
            if ($filters['explicit'] == 'yes' || $filters['explicit'] == 'no') {
                $query->where('explicit', $filters['explicit'] == 'yes');
            }
        }

        //TODO test invalid filters for this + dress
        return $query;
    }
}

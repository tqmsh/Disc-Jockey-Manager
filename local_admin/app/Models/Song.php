<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artist'
    ];

    public function getFullAttribute(): string{
        return $this->attributes['title'] . ' - ' . $this->attributes['artist'];
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    public function getFullAttribute(): string{
        return $this->attributes['title'] . ' - d' . $this->attributes['artist'];
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class Promfluencer extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'user_id',
        'instagram',
        'tiktok',
        'snapchat',
        'youtube',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

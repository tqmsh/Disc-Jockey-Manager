<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Wishlist extends Model
{
    use HasFactory;
    use AsSource;

    protected $table = 'dress_wishlist';

    protected $fillable = [
        'user_id',
        'dress_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function dress()
    {
        return $this->belongsTo(Dress::class, 'dress_id', 'id');
    }
}

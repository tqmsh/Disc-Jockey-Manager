<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Venues extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state_province',
        'country',
        'zip_postal',
        'website',
        'contact_first_name',
        'contact_last_name',
        'email',
        'phone',
        'created_at',
        'updated_at',
    ];

    // Optionally, you might want to set these timestamps to be managed by Laravel
    public $timestamps = true;
}

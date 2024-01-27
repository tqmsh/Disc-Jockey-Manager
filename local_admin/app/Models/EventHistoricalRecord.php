<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class EventHistoricalRecord extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'id',
        'event_id',
        'venue_name',
        'venue_notes',
        'disk_jockey_name',
        'disk_jockey_notes',
        'photobooth_name',
        'photobooth_notes',
        'photographer_name',
        'photographer_notes',
        'videographer_name',
        'videographer_notes',
        'created_at',
        'updated_at',
    ];

}
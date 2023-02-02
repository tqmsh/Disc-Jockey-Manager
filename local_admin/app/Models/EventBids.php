<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class EventBids extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'user_id',
        'event_id',
        'package_id',
        'event_venue_id',
        'event_date',
        'school_name',
        'notes',
        'status',
        'contact_instructions',
        'category_id',
        'company_name',
        'url',
        'created_at',
        'updated_at',
    ];

    //relationship to user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

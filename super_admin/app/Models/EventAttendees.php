<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class EventAttendees extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'user_id',
        'inviter_user_id',
        'event_id',
        'table_id',
        'invited',
        'table_approved',
        'invitation_status',
        'ticketstatus',
        'created_at',
        'updated_at',
    ];
}

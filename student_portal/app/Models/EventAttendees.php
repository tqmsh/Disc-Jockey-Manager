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
        'event_id',
        'table_id',
        'table_approved',
        'inviter_user_id',
        'invitation_status',
        'invited',
        'ticket_code',
        'ticketstatus',
        'created_at',
        'updated_at',
    ];
}

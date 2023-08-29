<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAttendees extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'inviter_user_id',
        'event_id', 
        'invitation_status',
        'invited',
        'table_id', 
        'ticketstatus', 
        'ticket_code',
        'created_at',
        'updated_at'
    ];
}

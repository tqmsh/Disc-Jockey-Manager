<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPollVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'poll_id',
        'poll_options_id',
        'created_at',
        'updated_at'
    ];
}

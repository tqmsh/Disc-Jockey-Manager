<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_name',
        'start_date',
        'end_date',
        'event_id',
        'school_id',
        'created_by',
        'updated_by',
    ];
}

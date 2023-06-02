<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'candidate_name',
        'candidate_bio',
        'election_id',
        'position_id',
        'created_at',
        'updated_at'
    ];
}

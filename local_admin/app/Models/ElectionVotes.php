<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionVotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'voter_user_id',
        'candidate_id',
        'position_id',
        'created_by',
        'updated_by',
    ];
}

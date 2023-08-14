<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionWinner extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'created_by',
        'updated_by',
    ];
}

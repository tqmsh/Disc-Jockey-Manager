<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentBids extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'student_id',
        'region_id',
        'package_id',
        'category_id',
        'school_name',
        'notes', 
        'contact_instructions',
        'company_name',
        'url',
        'status',
        'created_at',
        'updated_at'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class StudentBids extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'user_id',
        'student_id',
        'package_id',
        'school_name',
        'notes',
        'region_id',
        'status',
        'contact_instructions',
        'category_id',
        'company_name',
        'url',
        'created_at',
        'updated_at',
    ];    
}

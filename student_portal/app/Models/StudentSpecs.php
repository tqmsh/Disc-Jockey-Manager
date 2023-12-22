<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSpecs extends Model
{
    use HasFactory;
    use AsSource;


    protected $table = 'student_specs';
    
    protected $fillable = [
        'student_user_id', 
        'age', 
        'gender', 
        'hair_colour', 
        'hair_style', 
        'complexion', 
        'bust', 
        'waist',
        'hips',
        'height', 
        'weight', 
        'created_at', 
        'updated_at', 
    ];
}

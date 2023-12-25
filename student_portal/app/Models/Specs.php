<?php

namespace App\Models;

use Exception;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specs extends Model
{
    use HasFactory;
    use AsSource;

    protected $table = 'student_specs';

    protected $primaryKey = 'student_user_id';

    protected $fillable = [
        'student_user_id',
        'gender',
        'age',
        'height',
        'weight',
        'hair_colour',
        'hair_style',
        'complexion',
        'eye_colour',
        'lip_style',
        'bust',
        'waist',
        'hips',
        'notes',
        'body_type',
    ];
}

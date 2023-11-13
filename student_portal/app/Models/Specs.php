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

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id',
        'gender',
        'height',
        'weight_pounds',
        'hair_color',
        'hair_style',
        'hair_length',
        'skin_complexion',
        'eye_color',
        'lip_style',
        'bust',
        'waist',
        'hips',
        'notes',
        'body_type',
    ];
}

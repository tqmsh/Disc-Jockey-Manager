<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specs extends Model
{
    use HasFactory;
    use AsSource;

    protected $table = 'student_specs';

    protected $primaryKey = 'student_user_id';
}
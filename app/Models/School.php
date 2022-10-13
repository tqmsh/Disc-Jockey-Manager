<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class School extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = ['school_name', 'country', 'state_province', 'school_board', 'address', 'zip_postal', 'phone_number', 'fax', 'teacher_name', 'teacher_email', 'teacher_cell'];

        protected $allowedFilters = [
        'school_name',
        'country',
        'state_province',
        'school_board',
    ];

        protected $allowedSorts = [
        'id',
        'school_name',
        'country',
        'state_province',
        'school_board',
    ];

}

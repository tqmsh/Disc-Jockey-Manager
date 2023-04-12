<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_name',
        'ordering',
        'category',
        'updated_at',
        'created_at'
    ];

    public function sections()
    {
        return $this->hasMany(Section::class, 'course_id', 'id');
    }
}

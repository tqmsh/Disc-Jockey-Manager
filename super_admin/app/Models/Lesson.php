<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsMultiSource;
use Orchid\Screen\AsSource;

class Lesson extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'guide_id',
        'lesson_name',
        'ordering',
        'section_id',
        'lesson_description',
        'lesson_content',
        'updated_at',
        'created_at'
    ];

    public function guide()
    {
        return $this->belongsTo(Guide::class, 'guide_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
}

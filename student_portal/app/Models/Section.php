<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    
    public function lessons(){
        return $this->hasMany(Lesson::class, 'section_id', 'id');
    }
    
    public function guide()
    {
        return $this->belongsTo(Guide::class, 'guide_id', 'id');
    }
}

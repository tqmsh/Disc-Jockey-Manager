<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    use HasFactory;

    public function sections()
    {
        return $this->hasMany(Section::class, 'guide_id', 'id');
    }
}

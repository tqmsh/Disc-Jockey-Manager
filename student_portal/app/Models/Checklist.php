<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class Checklist extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'id',
        'name',
        'description',
        'type',
        'created_at',
        'updated_at'
    ];

    // Define one to many relationship with Checklist Items
    public function items() : HasMany {
        return $this->hasMany(ChecklistItem::class, 'checklist_id');
    }
}

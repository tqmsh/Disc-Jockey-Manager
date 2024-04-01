<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class ChecklistItem extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'id',
        'checklist_id',
        'title',
        'description',
        'created_at',
        'updated_at'
    ];

    public function checklist() : BelongsTo {
        return $this->belongsTo(Checklist::class, 'checklist_id');
    }
}

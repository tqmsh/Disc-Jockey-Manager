<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class ChecklistUser extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'id',
        'checklist_id',
        'checklist_item_id',
        'checklist_user_id',
        'created_at',
        'updated_at'
    ];

    public function checklist() : BelongsTo {
        return $this->belongsTo(Checklist::class, 'checklist_id');
    }

    public function checklist_item() : BelongsTo {
        return $this->belongsTo(ChecklistItem::class, 'checklist_item_id');
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'checklist_user_id');
    }
}

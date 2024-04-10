<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;

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

    public function scopeFilter($query, array $filters) {
        try {
            if(isset($filters['type'])) {
                $query->where('type', $filters['type']);
            }
        } catch(\Exception $e) {
            Alert::error('There was an error processing the filter. Error Message: ' . $e->getMessage());
        }
    }
}

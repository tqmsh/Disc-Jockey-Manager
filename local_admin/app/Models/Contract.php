<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;

class Contract extends Model
{
    use HasFactory, AsSource;

    protected $fillable = ['user_id', 'title', 'url', 'state_province', 'description',];

    public function scopeFilter($query, array $filters) {
        try {
            if (isset($filters['title'])) {
                $query->where('title', 'like', '%' . request('title') . '%');
            }
            if (isset($filters['state_province'])) {
                $query->where('state_province', 'like', '%' . request('state_province') . '%');
            }
            $query->select('contracts.*');
        } catch (Exception $e) {
            Alert::error('There was an error processing the filter. Error message: ' . $e->getMessage());
        }
    }
}

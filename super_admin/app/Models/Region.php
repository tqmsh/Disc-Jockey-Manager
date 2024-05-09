<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Support\Facades\Alert;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function scopeFilter($query, array $filters) {
        try {
            if (isset($filters['region_name'])) {
                $query->where('name', 'like', '%' . request('region_name') . '%');
            }
            $query->select('regions.*');
        } catch(Exception $e) {
            Alert::error('There was an error processing the filter. Error Message: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class VendorPaidRegions extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'user_id',
        'region_id',
        'created_at',
        'updated_at'
    ];
}

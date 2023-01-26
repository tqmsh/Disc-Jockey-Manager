<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_name',
        'description',
        'price',
        'user_id',
        'url',
        'created_at',
        'updated_at',
    ];

    //relationship to user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

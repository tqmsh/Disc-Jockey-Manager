<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class LimoGroupBid extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'id',
        'user_id',
        'limo_group_id',
        'region_id',
        'school_id',
        'package_id',
        'category_id',
        'school_name',
        'notes',
        'status',
        'company_name',
        'url',
        'contact_instructions',
        'created_at',
        'updated_at'
    ];

    public function limoGroup(){
        return $this->belongsTo(LimoGroup::class, 'limo_group_id', 'id');
    }
}

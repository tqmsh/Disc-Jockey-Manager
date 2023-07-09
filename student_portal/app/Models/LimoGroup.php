<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class LimoGroup extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'id',
        'name',
        'creator_user_id',
        'capacity',
        'date',
        'pickup_location',
        'dropoff_location',
        'depart_time',
        'dropoff_time',
        'notes',
        'created_at',
        'updated_at',
    ];

    public function owner()
    {
        return $this->belongsTo(Student::class, 'creator_user_id', 'user_id');
    }

    public function members(){
        return $this->hasMany(LimoGroupMember::class, 'limo_group_id', 'id');
    }
}

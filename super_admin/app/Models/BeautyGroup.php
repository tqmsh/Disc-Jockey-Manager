<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class BeautyGroup extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'id',
        'name',
        'creator_user_id',
        'school_id',
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
        return $this->hasMany(BeautyGroupMember::class, 'beauty_group_id', 'id');
    }

    public function activeMembers(){
        return $this->hasMany(BeautyGroupMember::class, 'beauty_group_id', 'id')->where('status', 1);
    }

    public function school(){
        return $this->belongsTo(School::class, 'school_id', 'id');
    }
}

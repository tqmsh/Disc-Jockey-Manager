<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class BeautyGroupMember extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'beauty_group_id',
        'limo_group_id',
        'invitee_user_id',
        'status',
        'paid',
        'created_at',
        'updated_at',
    ];

    //associate with LimoGroup
    public function beautyGroup()
    {
        return $this->belongsTo(BeautyGroup::class, 'beauty_group_id', 'id');
    }

    //associate with User
    public function user()
    {
        return $this->belongsTo(Student::class, 'invitee_user_id', 'user_id');
    }
}

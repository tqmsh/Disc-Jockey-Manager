<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class LimoGroupMember extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'limo_group_id',
        'invitee_user_id',
        'is_leader',
        'is_active',
    ];

    //associate with LimoGroup
    public function limoGroup()
    {
        return $this->belongsTo(LimoGroup::class, 'limo_group_id', 'id');
    }

    //associate with User
    public function user()
    {
        return $this->belongsTo(Student::class, 'invitee_user_id', 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Orchid\Platform\Models\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'firstname',
        'lastname',
        'country',
        'role',
        'phonenumber',
        'name',
        'email',
        'account_status',
        'pfp',
        'permissions',
        'password',
        'updated_at',
        'created_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'firstname',
        'lastname',
        'country',
        'role',
        'phonenumber',
        'name',
        'email',
        'updated_at',
        'created_at',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'firstname',
        'lastname',
        'country',
        'role',
        'phonenumber',
        'name',
        'pfp',
        'email',
        'updated_at',
        'created_at',
    ];

    //create relationship with student bids
    public function bids(){
        return $this->hasMany(StudentBids::class, 'student_id');
    }

    public function student(){
        return $this->hasOne(Student::class, 'user_id');
    }

    public function limoGroup(){
        return $this->hasOne(LimoGroup::class, 'creator_user_id');
    }

    public function beautyGroup(){
        return $this->hasOne(BeautyGroup::class, 'creator_user_id');
    }

    public function promfluencer(){
        return $this->hasOne(Promfluencer::class);
    }

    public function fullName()
    {
        return $this->firstname." ".$this->lastname;
    }
}

<?php

namespace App\Models;

use Orchid\Platform\Models\User as Authenticatable;

class User extends Authenticatable
{
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
        'permissions',
        'password',
        'pfp',
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
        'email',
        'updated_at',
        'created_at',
    ];

    public function localadmin(){
        return $this->hasOne(Localadmin::class, 'user_id', 'id');
    }
}

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
        'email',
        'updated_at',
        'created_at',
    ];

    //relationsip with packages
    public function packages()
    {
        return $this->hasMany(VendorPackage::class, 'user_id');
    }

    //relationshp with paid regions
    public function paidRegions()
    {
        return $this->hasMany(VendorPaidRegions::class, 'user_id');
    }

    //relationship with event bids
    public function eventBids()
    {
        return $this->hasMany(EventBids::class, 'user_id');
    }

    //relationship with student bids
    public function studentBids()
    {
        return $this->hasMany(StudentBids::class, 'user_id');
    }
}

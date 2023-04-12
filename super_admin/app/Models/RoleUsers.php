<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class RoleUsers extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = ['user_id', 'role_id'];

}

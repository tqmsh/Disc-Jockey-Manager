<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Localadmin extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = ['user_id', 'firstname', 'lastname', 'phonenumber', 'email', 'school'];
}

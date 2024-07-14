<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Staffs extends Model
{
    use HasFactory;
    use AsSource;
 
    protected $table = 'staffs';

    protected $fillable = [
        'first_name', 'last_name', 'position', 'gender', 'email', 'cell', 'age'
    ];
 
}

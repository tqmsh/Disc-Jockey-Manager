<?php

namespace App\Models;

use Exception;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversalExpenseRevenue extends Model
{
    use HasFactory;
    use AsSource;

    protected $table = 'universal_expenses_revenues';
    protected $fillable = ['name', 'created_at', 'type', 'updated_at', 'last_updated_user_id'];
}

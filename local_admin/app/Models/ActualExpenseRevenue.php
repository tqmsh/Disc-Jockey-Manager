<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActualExpenseRevenue extends Model
{
    use HasFactory;
    use AsSource;


    protected $table = 'actual_expenses_revenues';
    
    protected $fillable = [
        'universal_id', 
        'event_id', 
        'type', 
        'budget', 
        'actual', 
        'created_at', 
        'updated_at', 
        'last_updated_user_id'
    ];

    /*public function event(): BelongsTo
    {
        return $this->belongsTo(Events::class);
    }*/
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class SchoolDresses extends Model
{
    use HasFactory;
    use AsSource;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'school_dresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'dress_id',
        'user_id',
    ];

    /**
     * Get the event that owns the EventDress.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the dress that owns the EventDress.
     */
    public function dress()
    {
        return $this->belongsTo(Dress::class);
    }

    /**
     * Get the user that owns the EventDress.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

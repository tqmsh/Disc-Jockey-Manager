<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class EventBids extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'user_id',
        'event_id',
        'package_id',
        'event_venue_id',
        'event_date',
        'school_name',
        'notes',
        'status',
        'contact_instructions',
        'category_id',
        'company_name',
        'url',
        'created_at',
        'updated_at',
    ];

    //scope filter for region id
    public function scopeFilter($query, array $filters)
    {
        if(isset($filters['region_id'])){
            $query->where('region_id', $filters['region_id']);
        }

        $query->get();
    }

    //relationship to user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

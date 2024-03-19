<?php

namespace App\Models;

use Exception;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Orchid\Support\Facades\Alert;

class CoupleRequest extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'owner_user_id',
        'receiver_user_id',
        'event_id'
    ];

    public function scopeFilter($query, array $filters){

        try{

            if(isset($filters['event_id'])){
                $query->where('event_id', $filters['event_id']);
            }

            if(isset($filters['owner_id'])){
                $query->where('owner_user_id', $filters['owner_id']);
            }

            if(isset($filters['receiver_id'])){
                $query->where('receiver_user_id', $filters['receiver_id']);
            }

            $query->select('couple_requests.*');

        }catch(Exception $e){

            Alert::error('There was an error processing the scope filter. Error Message: ' . $e->getMessage());
        }

    }

    // relationship to owner
    public function owner()
    {
        return $this->belongsTo(Student::class, 'owner_user_id', 'user_id');
    }

    // relationship to "invited" user
    public function invited()
    {
        return $this->belongsTo(Student::class, 'receiver_user_id', 'user_id');
    }

    // relation to school
    public function event(){
        return $this->belongsTo(Events::class, 'event_id', 'id');
    }
}

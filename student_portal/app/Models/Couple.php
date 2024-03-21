<?php

namespace App\Models;

use Exception;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Orchid\Support\Facades\Alert;

class Couple extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'student_user_id_1',
        'student_user_id_2',
        'event_id',
        'couple_name',
        'status',
        'description'
    ];

    public function scopeFilter($query, array $filters){

        try{

            if(isset($filters['event_id'])){
                $query ->where('event_id', $filters['event_id']);
            }

            $query->get();

        }catch(Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e->getMessage());
        }
    }

    // relationship to first user
    public function user1()
    {
        return $this->belongsTo(Student::class, 'student_user_id_1', 'user_id');
    }

    // relationship to second user
    public function user2()
    {
        return $this->belongsTo(Student::class, 'student_user_id_2', 'user_id');
    }

    // relation to school
    public function event(){
        return $this->belongsTo(Events::class, 'event_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionWinner extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'position_id',
        'created_by',
        'updated_by',
    ];

    public function totalVotes($candidate_id)
    {   
        $totalVotes = 0;
        $allVoters = ElectionVotes::where('candidate_id',$candidate_id)->get();

        if(!empty($allVoters)){

            foreach($allVoters as $voter){
                $totalVotes+=1;
            }

        }
        return $totalVotes;
    }
}

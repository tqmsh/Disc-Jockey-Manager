<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'candidate_name',
        'candidate_bio',
        'candidate_video_url',
        'election_id',
        'position_id',
        'created_at',
        'updated_at'
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

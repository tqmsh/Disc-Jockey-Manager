<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Events;
use App\Models\User;
use App\Models\Election;
use App\Models\ElectionVote;
use App\Models\Candidate;
use App\Models\EventAttendees;
use App\Models\Localadmin;
use App\Models\Position;

class PromVoteController extends Controller
{
    /**
     * Get vote data from an event, for a specific user if possible.
     *
     * @param Request $request
     * @param Events $event
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function getVoteData($event_id, $user_id)
    {
        // Only allow the attending student whose vote is being checked, super admins,
        // and local admins for the event's school to get vote data.
        $auth_user = request()->user();
        if (!(
            (
                $auth_user->id == $user_id
                and EventAttendees::where('user_id', $user_id)
                    ->where('event_id', $event_id)
                    ->exists()
            )
            or $auth_user->role == 1
            or Localadmin::where('user_id', $auth_user->id)
                ->where('school_id', Events::findOrFail($event_id)->school_id)
                ->exists()
        )) {
            return response()->json(['message' => 'You are not authorized to get the vote data'], 401);
        }

        $vote_data = [];
        $election = Election::firstWhere('event_id', $event_id);

        $vote_data['hasVote'] = !is_null($election) and $this->isActive($election);
        if (!$vote_data['hasVote']) {
            return $vote_data;
        }

        $vote = ElectionVote::where('election_id', $election->id)
                            ->where('voter_user_id', $user_id)
                            ->first();
                            
        $vote_data['hasAlreadyVoted'] = !is_null($vote);
        if ($vote_data['hasAlreadyVoted']) {
            $vote_data['vote'] = Candidate::find($vote->candidate_id)->candidate_name;
            return $vote_data;
        }

        $position_objects = Position::where('election_id', $election->id)->get();
        $vote_data['positions'] = [];
        foreach ($position_objects as $position) {
            $vote_data['positions'][] = [
                'name' => $position->position_name,
                'candidate' => Candidate::where('position_id', $position->id)
                                        ->pluck('candidate_name')
                                        ->toArray(),
            ];
        }
        return $vote_data;
    }

    /**
     * Check whether or not an election is active (current datetime is between start and end).
     * 
     * @param Election $election
     * @return bool
     */
    protected function isActive(Election $election)
    {
        $current_datetime = now();
        return ($election->start_date < $current_datetime && $current_datetime < $election->end_date);
    }
}
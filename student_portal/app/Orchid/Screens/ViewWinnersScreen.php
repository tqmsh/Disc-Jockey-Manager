<?php

namespace App\Orchid\Screens;

use App\Models\Election;
use App\Models\Events;
use App\Models\Position;
use App\Models\Candidate;

use Orchid\Screen\Screen;
use App\Models\EventAttendees;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewWinnersLayout;

class ViewWinnersScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Election $election) : iterable
    {
        $event = Events::where('id',$election->event_id)->first();
        $candidates = Candidate::where('election_id', $election->id)->get();
        $positions = Position::where('election_id', $election->id)->get();
        
        // TODO probably best to give user a warning instead
        $studentAttendee= EventAttendees::where('user_id', Auth::user()->id)->where('event_id', $event->id)->first();
        abort_if(!($studentAttendee->exists() &&  $studentAttendee-> ticketstatus == 'Paid'), 403);
        $election = Election::where('id',$election->id)->first();

        
        return [
            'election' => $election,
            'event' => $event,
            'positions' => $positions,
            'candidates' => $candidates,
        ];
    }  

    /**
     * The name of the screen is displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return "Election Winners";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout() : array
    {
        return [
            ViewWinnersLayout::class
        ];
    }

    public function winners_check($positions, $candidates)
    {
        $highestVotes = 0;
        foreach($positions as $position)
        {
            // Check for highest number of votes
            foreach($candidates as $candidate)
            {
                if ($candidate->totalVotes() > $highestVotes)
                {
                    $highestVotes = $candidate->totalVotes();
                }
            }
            // Fill array with candidates with highest votes
            foreach($candidates as $candidate)
            {
                if ($candidate->totalVotes() > $highestVotes)
                {
                    $highestVotes = $candidate->totalVotes();
                }
            }
        }
    }
}

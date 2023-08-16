<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use App\Models\Election;
use App\Models\ElectionWinner;
use App\Models\Position;
use App\Models\Candidate;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ViewWinnersPositionScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Position $position): iterable
    {
        $winner_ids = [];
        $winners = ElectionWinner::where('position_id', $position->id)->get();
        foreach($winners as $winner)
        {
            $winner_ids[] = $winner->candidate_id;
        }

        $winningCandidates = Candidate::whereIn('id', $winner_ids)->get();

        return [
            'winners' => $winners,
            'winning_candidates' => $winningCandidates,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'ViewWinnersPositionScreen';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('winner_position')
        ];
    }
}

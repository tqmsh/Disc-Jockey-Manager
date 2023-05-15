<?php

namespace App\Orchid\Screens;

use App\Models\Election;
use App\Models\Candidate;
use Orchid\Screen\Screen;
use App\Models\Localadmin;
use Illuminate\Support\Facades\Auth;

class EditCandidateScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Candidate $candidate): iterable
    {
        // $election = Election::where('id', $candidate->election_id)->first();
        // abort_if(Localadmin::where('user_id', Auth::user()->id)->first()->school_id != $election->school_id, 403, 'You are not authorized to view this page.');
        return [
            'candidate' => $candidate,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'EditCandidateScreen';
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
        return [];
    }
}

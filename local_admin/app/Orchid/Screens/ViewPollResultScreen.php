<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;

use App\Models\Poll;
use App\Models\PollOption;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\VotePollLayout;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentPollVote;
use App\Orchid\Layouts\VotePollLayoutVoted;
use Orchid\Support\Facades\Layout;

use App\Orchid\Layouts\ViewPollResultsLayout;

class ViewPollResultScreen extends Screen
{
    public $poll;
    public $options;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Poll $poll): iterable
    {
        $options = PollOption::where('poll_id', $poll->id)->get();

        return [
            'poll' => $poll,
            'options' => $options,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Results for the poll: ' .$this->poll->title;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
            ->icon('arrow-left')
            ->route('platform.all.polls')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ViewPollResultsLayout::class,
        ];
    }
}

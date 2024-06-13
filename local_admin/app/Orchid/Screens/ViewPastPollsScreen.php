<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;

use App\Models\Poll;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\ViewPollsLayout;
use App\Orchid\Layouts\ViewPastPollsLayout;
use App\Models\Localadmin;

class ViewPastPollsScreen extends Screen
{
    public $polls;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $user = Auth::user();
        $localAdmin = Localadmin::where('user_id',$user->id)->first();

        $pastPolls = Poll::where('school_id', $localAdmin->school_id)
             ->where('end_date', '<', now())
             ->get();

        return [
            'polls' => $pastPolls,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Past Polls';
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
        ];    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ViewPastPollsLayout::class,
        ];
    }

    public function redirect_poll($poll){
        $poll = Poll::find(request('poll'));
        return redirect()->route('platform.poll.result', $poll->id);
    }
}

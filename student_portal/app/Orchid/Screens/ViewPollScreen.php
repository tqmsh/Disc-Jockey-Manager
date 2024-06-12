<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;

use App\Models\Poll;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\ViewPollsLayout;


class ViewPollScreen extends Screen
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
        $student = Student::where('user_id',$user->id)->first();

        $polls = Poll::where('school_id', $student->school_id)
                 ->where('start_date', '<=', now())
                 ->where('end_date', '>=', now())
                 ->get();

        return [
            'polls' => $polls,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'LIVE Polls';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Past Polls')
                ->icon('clock')
                ->route('platform.poll.past'),
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
            ViewPollsLayout::class,
        ];
    }

    public function redirect_poll($poll){
        $poll = Poll::find(request('poll'));
        return redirect()->route('platform.poll.vote', $poll->id);
    }



}

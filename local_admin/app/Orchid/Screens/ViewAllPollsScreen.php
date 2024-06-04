<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;

use App\Orchid\Layouts\ViewAllLivePollsLayout;
use App\Models\Localadmin;
use Illuminate\Support\Facades\Auth;
use App\Models\Poll;
use Carbon\Carbon;
use Orchid\Screen\Actions\DropDown;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;
use App\Orchid\Screens\EditPollScreen;
use App\Models\PollOption;


class ViewAllPollsScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $local_admin = Localadmin::where('user_id', Auth::user()->id)->first();
        $school_id = $local_admin->school_id;
        $polls = Poll::where('school_id', $school_id)->where('end_date', '>', Carbon::now())->get();

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
            DropDown::make('Poll Options')
                ->icon('options-vertical')
                ->list([
    
                    Link::make('Edit Poll')
                        ->icon('pencil'),
                        // ->route('platform.poll.edit',$this->election->id),
    
                    Button::make('Delete Poll')
                        ->icon('trash')
                        ->method('deletePolls')
                        ->confirm(__('Are you sure you want to DELETE this election? 🚨🚨🚨This action is PERMENANT and cannot be UNDONE🚨🚨🚨
                        In order to END an election, change the elections end date to any date in the past.')),
                ]),

            Link::make('Create Poll')
                ->icon('plus')
                ->route('platform.create.poll'),

            Link::make('Past Polls')
                ->icon('clock')
                ->route('platform.past.polls')
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
            ViewAllLivePollsLayout::class
        ];
    }

    public function deletePolls(Request $request)
    {   
        // dd('123');
        //get all localadmins from post request
        $polls = $request->get('polls');
        // dd($polls);
        try{
            //if the array is not empty
            if(!empty($polls)){

                Poll::whereIn('id', $polls)->delete();

                foreach ($polls as $poll) {
                    PollOption::where('poll_id', $poll)->delete();
                }

                Toast::success('Selected Polls deleted succesfully');

            }else{
                Toast::warning('Please select Polls in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected Polls. Error Message: ' . $e->getMessage());
        }
    }

    public function edit_poll($poll){
        $poll = Poll::find(request('poll'));
        return redirect()->route('platform.poll.edit', $poll->id);
    }

    public function redirect_poll($poll){
        $poll = Poll::find(request('poll'));
        return redirect()->route('platform.poll.result', $poll->id);
    }

}


<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use App\Models\Poll;
use App\Models\Option;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use App\Models\Localadmin;
use App\Models\PollOption;
use Exception;
use Carbon\Carbon;


use Illuminate\Http\Request;



class CreatePollScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Create Poll';
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
           Layout::view('Polls/create-poll') 
        ];
    }

    public function create(Request $request, Poll $poll)
    {

        try{

            $local_admin = Localadmin::where('user_id', Auth::user()->id)->first();
            $school_id = $local_admin->school_id;

            $electionField = $request->all();
            $electionField['school_id'] = $school_id;
            $electionField['approved'] = 1;
            $electionField['start_date'] = Carbon::createFromFormat('m/d/Y', $request->start_date)->startOfDay();
            $electionField['end_date'] = Carbon::createFromFormat('m/d/Y', $request->end_date)->endOfDay();

            // dd(Carbon::createFromFormat('m/d/Y', $request->end)->endOfDay());
            

            Poll::create($electionField);

            // $correct_poll = Poll::where('school_id', $school_id)->latest()->first();
            // $poll_id = $correct_poll->id;

            $options = collect($request->except(['title', 'description', 'start_date', 'end_date']))->filter(); // filter to remove null or empty values
            // dd($options);
            $options->each(function ($value, $key) use ($poll) {
                $local_admin = Localadmin::where('user_id', Auth::user()->id)->first();
                $school_id = $local_admin->school_id;

                $correct_poll = Poll::where('school_id', $school_id)->latest()->first();
                $poll_id = $correct_poll->id;

                PollOption::create([
                    'title' => $value,
                    'poll_id' => $poll_id,
                ]);
            });

            Toast::success('Poll created Succesfully');
            
            return redirect()->route('platform.all.polls');

        }catch(Exception $e){
            
            Alert::error('There was an error creating this Poll. Error Code: ' . $e->getMessage());
        }
        }
}

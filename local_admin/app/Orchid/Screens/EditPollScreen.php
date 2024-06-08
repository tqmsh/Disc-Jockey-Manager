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
use Orchid\Support\Facades\Layout;
use App\Models\PollOption;


class EditPollScreen extends Screen
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
        $poll['start_date'] = Carbon::parse($poll->start_date)->format('m/d/Y');
        $poll['end_date'] = Carbon::parse($poll->end_date)->format('m/d/Y');

        $options = PollOption::where('poll_id', $poll->id)->get();
        // dd($options[0]->title);
        return [
            'poll' => $poll,
            'options' => $options
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Poll ' .$this->poll->title;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            // Button::make('Submit')
            //     ->icon('check')
            //     ->method('update'),

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
        // $poll = $this->poll;
        $poll = "florence test";
        return [
            Layout::view('Polls/edit-poll')
        ];
    }

    public function update(Request $request, Poll $poll)
    {

        try{

            $electionField = $request->all();

            $electionField['start_date'] = Carbon::createFromFormat('m/d/Y', $request->start_date)->startOfDay();
            $electionField['end_date'] = Carbon::createFromFormat('m/d/Y', $request->end_date)->startOfDay();
            
            $poll->update($electionField);

            $options = collect($request->all())->filter(function ($value, $key) {
                return strpos($key, 'option_') === 0;
            }, ARRAY_FILTER_USE_BOTH);

            // dd($options);

            $existingOptions = PollOption::where('poll_id', $poll->id)->pluck('title', 'id');

            // Delete removed options
            foreach ($existingOptions as $optionId => $optionTitle) {
                $found = false;
                foreach ($options as $value) {
                    if ($value === $optionTitle) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    PollOption::destroy($optionId);
                }
            }

            $pollId = $poll->id;

            // Add new options
            $options->each(function ($value) use ($pollId) {
                $local_admin = Localadmin::where('user_id', Auth::user()->id)->first();
                $school_id = $local_admin->school_id;

                $correct_poll = Poll::where('school_id', $school_id)->latest()->first();
                $poll_id = $correct_poll->id;

                // dd($value);

                if (!PollOption::where('poll_id', $poll_id)->where('title', $value)->exists()) {
                    // dd($value);
                    PollOption::create([
                        'title' => $value,
                        'poll_id' => $poll_id,
                    ]);
                }
            });

            Toast::success('Poll Updated Succesfully');
            
            return redirect()->route('platform.all.polls');

        }catch(Exception $e){
            
            Alert::error('There was an error updating this Poll. Error Code: ' . $e->getMessage());
        }
    }


}

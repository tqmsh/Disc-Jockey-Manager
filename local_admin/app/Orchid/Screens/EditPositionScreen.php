<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Election;
use App\Models\Position;
use Orchid\Screen\Screen;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;

class EditPositionScreen extends Screen
{
    public $position;
    public $election;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Position $position): iterable
    {
        $election = Election::where('id', $position->election_id)->first();
        abort_if(Localadmin::where('user_id', Auth::user()->id)->first()->school_id != $election->school_id, 403, 'You are not authorized to view this page.');
        return [
            'position' => $position,
            'election' => $election
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Position: ' .$this->election->position_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Submit')
                ->icon('check')
                ->method('update'),
                
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.eventPromvote.list', $this->election->event_id)
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
            Layout::rows([

                Input::make('position_name')
                    ->title('Position Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->position->position_name),

            ])->title('Edit This Position'),
        ];
    }

    public function update(Position $position, Request $request)
    {
        $election = Election::where('id', $position->election_id)->first();
        try{

            $positionFields = $request->all();

            $position->update($positionFields);

            Toast::success('You have successfully updated ' . $request->input('election_name') . '.');

            return redirect()->route('platform.eventPromvote.list',$election->event_id);

        }catch(Exception $e){

            Alert::error('There was an error editing this event. Error Code: ' . $e->getMessage());
        }
    }
}

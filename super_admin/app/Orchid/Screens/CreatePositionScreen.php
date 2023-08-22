<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Election;
use App\Models\Position;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;


class CreatePositionScreen extends Screen
{
    public $election;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Election $election): iterable
    {
        return [
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
        return 'Add New Position to: ' . $this->election->election_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Add')
                ->icon('plus')
                ->method('createPosition',[$this->election]),
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
                    ->placeholder('New Position') 
                    ->horizontal(),

            ])->title('Make a Position'),
        ];
    }

    public function createPosition(Request $request, Election $election){

        try{

            $positionField = $request->all();
            $positionField['election_id'] = $election->id;

            Position::create($positionField);

            Toast::success('Position Added Succesfully');
            
            return redirect()->route('platform.eventPromvote.list',$election->event_id);

        }catch(Exception $e){
            
            Alert::error('There was an error creating this position. Error Code: ' . $e->getMessage());
        }
    }
}

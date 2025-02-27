<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\School;
use App\Models\Election;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewEventLayout;

class ViewEventScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'events' => Events::latest('events.created_at')->filter(request(['country', 'state_province', 'school', 'school_board']))->paginate(min(request()->query('pagesize', 10), 100))
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Events';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Add New Events')
                ->icon('plus')
                ->route('platform.event.create'),

            Button::make('Delete Selected Events')
                ->icon('trash')
                ->method('deleteEvents')
                ->confirm(__('Are you sure you want to delete the selected events?')),

                
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list')
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

                Group::make([
                    
                    Select::make('school')
                        ->title('School')
                        ->empty('Start typing to search...')
                        ->help('Type in boxes to search')
                        ->fromModel(Events::class, 'school', 'school'),

                    Select::make('country')
                        ->title('Country')
                        ->empty('Start typing to search...')
                        ->fromModel(School::class, 'country', 'country'),

                    Select::make('school_board')
                        ->title('School Board')
                        ->empty('Start typing to search...')
                        ->fromModel(School::class, 'school_board', 'school_board'),

                    Select::make('state_province')
                        ->title('State/Province')
                        ->empty('Start typing to search...')
                        ->fromModel(School::class, 'state_province', 'state_province'),
                ]),
                
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewEventLayout::class
        ];
    }

    public function filter(){
        return redirect()->route('platform.event.list', request(['school', 'country', 'school_board', 'state_province']));
    }

    public function deleteEvents(Request $request)
    {   
        //get all events from post request
        $events = $request->get('events');
        
        try{

            //if the array is not empty
            if(!empty($events)){

                //delete all events in the array
                Events::whereIn('id', $events)->delete();

                Toast::success('Selected events deleted succesfully');

            }else{
                Toast::warning('Please select events in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected events. Error Message: ' . $e->getMessage());
        }
    }

    public function redirect($event, $type){
        if($type == 'event'){
            return redirect()->route('platform.eventBids.list', $event);
        } 
        else if($type == 'promvote'){
            //check if election is already created
            // if created, go to page to view position
            $election = Election::where('event_id', $event)->first();
            if($election != null){
                return redirect() -> route('platform.eventPromvote.list', $event);
            }
            // if not created, go to create
            else{
                return redirect() -> route('platform.eventPromvote.create', $event);
            }
        }
        else if($type == 'edit'){
            return redirect() -> route('platform.event.edit', $event);
        }
        else if($type == 'songReq'){
            return redirect()->route('platform.songreq.list', $event);
        }
        else {
            return redirect()->route('platform.eventStudents.list', $event);
        }    
    }
}

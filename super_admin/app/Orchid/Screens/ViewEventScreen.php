<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\School;
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
            'events' => Events::latest('events.created_at')->filter(request(['country', 'state_province', 'school', 'school_board']))->paginate(10)
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
                        ->empty('No selection')
                        ->fromModel(Events::class, 'school', 'school'),

                    Select::make('country')
                        ->title('Country')
                        ->empty('No selection')
                        ->fromModel(School::class, 'country', 'country'),

                    Select::make('school_board')
                        ->title('School Board')
                        ->empty('No selection')
                        ->fromModel(School::class, 'school_board', 'school_board'),

                    Select::make('state_province')
                        ->title('State/Province')
                        ->empty('No selection')
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

    public function filter(Request $request){
        return redirect('/admin/events?' 
                    .'&school=' . $request->get('school')
                    .'&country=' . $request->get('country')
                    .'&school_board=' . $request->get('school_board')
                    .'&state_province=' . $request->get('state_province'));
    }

    public function deleteEvents(Request $request)
    {   
        //get all events from post request
        $events = $request->get('events');
        
        try{

            //if the array is not empty
            if(!empty($events)){

                //loop through the events and delete them from db
                foreach($events as $event){
                    Events::where('id', $event)->delete();
                }

                Toast::success('Selected events deleted succesfully');

            }else{
                Toast::warning('Please select events in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected events. Error Message: ' . $e);
        }
    }
}

<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\School;
use App\Models\Election;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
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
            'events' => Events::where('school_id', Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id'))->filter(request(['event', 'sort_option',]))->latest('events.created_at')->paginate(10),
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
            Link::make('Add New Event')
                ->icon('plus')
                ->route('platform.event.create'),

            Link::make('Suggest a Vendor')
                ->icon('plus')
                ->route('platform.suggestVendor.create'),

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

                    Select::make('event')
                        ->title('Search Events')
                        ->help('Type in boxes to search')
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
                        ->help('Type in boxes to search')
                        ->empty('No selection')
                        ->fromQuery(Events::query()->where('school_id', Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id')), 'event_name', 'event_name'),

                    Select::make('sort_option')
                        ->title('Order Events By')
                        ->empty('No selection')
                        ->options([
                            'event_start_time ASC' => 'Start Date/Time (Earliest First)',
                            'event_start_time DESC' => 'Start Date/Time (Latest First)',
                            'event_finish_time ASC' => 'End Date/Time (Earliest First)',
                            'event_finish_time DESC' => 'End Date/Time (Latest First)',
                        ])
                ]),

                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewEventLayout::class,

         Layout::view("events_tour"),

        ];
    }

    public function filter(){
        return redirect()->route('platform.event.list', request(['event', 'sort_option',]));
    }

    public function deleteEvents(Request $request)
    {
        //get all localadmins from post request
        $events = $request->get('events');

        try{

            //if the array is not empty
            if(!empty($events)){

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
        switch($type){
            case 'event':
                return redirect()->route('platform.eventBids.list', $event);
            case 'promvote':
                $election = Election::where('event_id', $event)->first();
                if($election != null){
                    return redirect() -> route('platform.eventPromvote.list', $event);
                }
                else{
                    return redirect() -> route('platform.eventPromvote.create', $event);
                }
            case 'edit':
                return redirect() -> route('platform.event.edit', $event);
            case 'songReq':
                return redirect()->route('platform.songreq.list', $event);
            case 'student':
                return redirect()->route('platform.eventStudents.list', $event);
            case 'food':
                return redirect()->route('platform.eventFood.list', $event);
            case 'createHistory':
                return redirect()->route('platform.eventHistory.create', $event);
            case 'editHistory':
                return redirect()->route('platform.eventHistory.edit', $event);
            case 'profit':
                return redirect()->route('platform.budget.list', $event);
        }
    }
}

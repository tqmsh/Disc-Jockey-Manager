<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\School;
use App\Models\Vendors;
use App\Models\Clients; // Edit, cloned vendors into clients
use App\Models\Staffs; 
use App\Models\Systems; 
use App\Models\Song; 
use Orchid\Screen\Screen;
use App\Models\Categories;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\ModalToggle;

class EditEventScreen extends Screen
{
    public $event;
    public $school;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        return [
            'event' => $event
        ];
    }
    

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Event: ' . $this->event->event_name;
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

            Button::make('Delete Event')
                ->icon('trash')
                ->method('delete'),

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
            Layout::modal('suggestCategoryModal', [
                Layout::rows([
                    Input::make('category_name')
                        ->title('Category Name')
                        ->placeholder('Enter the name of the category')
                        ->help('Suggest a category to be reviewed and approved by an admin')
                        ->required(),
                ])
            ])->title('Suggest Category')
                ->applyButton('Suggest'),
                
            Layout::rows([

                Input::make('event_name')
                    ->title('Event Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->event->event_name), 

                Select::make('client_id')
                    ->title('Client')
                    ->fromQuery(Clients::query(), 'full_name')
                    ->horizontal()
                    ->value($this->event->client_id)
                    ->empty('Start typing to search...'), 

                DateTimer::make('event_date')
                    ->title('Event Date')
                    ->required() 
                    ->horizontal()
                    ->allowInput()
                    ->enableTime(false) // Disable time selection 
                    ->value($this->event->event_date),
            
                DateTimer::make('event_loadin_time')
                    ->title('Event Loadin Time')
                    ->required()
                    ->horizontal()
                    ->allowInput()
                    ->enableTime()
                    ->value($this->event->event_loadin_time),

                DateTimer::make('event_start_time')
                    ->title('Event Start')
                    ->required()
                    ->horizontal()
                    ->allowInput()
                    ->enableTime()
                    ->value($this->event->event_start_time),

                DateTimer::make('event_finish_time')
                    ->title('Event End')
                    ->required()
                    ->horizontal()
                    ->allowInput()
                    ->enableTime()
                    ->value($this->event->event_finish_time),
                    
                // edit: deleted event_address, event_zip_postal, event_info, event_rules, ticket_price, capacity, 
                Select::make('venue_id')
                    ->title('Venue')
                    ->fromQuery(Vendors::query()->where('category_id', Categories::where('name', 'LIKE', '%'. 'Venue' . '%')->first()->id), 'company_name')
                    ->horizontal()
                    ->value($this->event->venue_id)
                    ->empty('Start typing to search...'),

                Select::make('staff_id')
                    ->title('Staff')
                    ->fromQuery(
                        Staffs::query()->selectRaw("id, CONCAT(first_name, ' ', last_name) AS name"),
                        'name' 
                    )
                    ->horizontal()
                    ->value($this->event->staff_id)
                    ->empty('Start typing to search...'),

                Select::make('system_id')
                    ->title('System')
                    ->fromQuery(Systems::query(), 'full_name')
                    ->horizontal()
                    ->value($this->event->system_id)
                    ->empty('Start typing to search...'),
                // dd($this->event->song_ids),
                Select::make('song_ids')
                    ->title('Songs')
                    ->multiple()
                    ->fromQuery(Song::query(), 'title')
                    ->horizontal()
                    ->value(array_map('intval', $this->event->song_ids)) // Ensure values are cast to integers
                    ->empty('Start typing to search...'), 
                ModalToggle::make('Suggest Category')
                    ->modal('suggestCategoryModal')
                    ->method('suggestCategory')
                    ->icon('plus')
                    ->class('btn btn-default'),
            ]),            
        ];
    }

    public function update(Events $event, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_name' => 'required|max:255',
                'event_date' => 'required|date',
                'event_loadin_time' => 'required|date',
                'event_start_time' => 'required|date',
                'event_finish_time' => 'required|date|after_or_equal:event_start_time',
                'venue_id' => [
                    'nullable',
                    'int',
                    Rule::in(
                        Vendors::where(
                            'category_id',
                            Categories::where('name', 'Venue')->first()->id
                        )->pluck('id')
                    )
                ],
                'client_id' => 'nullable|int|exists:clients,id',
                'staff_id' => 'nullable|int|exists:staffs,id',
                'system_id' => 'nullable|int|exists:systems,id',
                'song_ids' => 'nullable|array',
            ]);
    
            $validated = $validator->validated();
            $event->update($validated);
    
            Toast::success('You have successfully updated ' . $request->input('event_name') . '.');
    
            return redirect()->route('platform.event.list');
        } catch (Exception $e) {
            Alert::error('There was an error editing this event. Error Code: ' . $e->getMessage());
        }
    }
    
    

    public function delete(Events $event)
    {
        try{

            $event->delete();

            Toast::success('You have successfully deleted the event.');

            return redirect()->route('platform.event.list');

        }catch(Exception $e){

            Alert::error('There was an error deleting this event. Error Code: ' . $e->getMessage());
        }     
    }

    public function suggestCategory()
    {
        $validator = Validator::make(request()->all(), [
            'category_name' => 'required|max:255|unique:categories,name',
        ]);
        Categories::create(['name' => $validator->validated()['category_name']]);
        Toast::success('Category suggested succesfully');
    }
}

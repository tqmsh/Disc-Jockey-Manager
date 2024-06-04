<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\School;
use App\Models\Vendors;
use Orchid\Screen\Screen;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;

class CreateEventScreen extends Screen
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
        return 'Add a New Event';
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
                ->method('createEvent'),

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

                Input::make('event_name')
                    ->title('Event Name')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. Prom 2022')
                    ->horizontal(),

                DateTimer::make('event_start_time')
                    ->title('Event Start')
                    ->required()
                    ->horizontal()
                    ->allowInput()
                    ->enableTime(),

                DateTimer::make('event_finish_time')
                    ->title('Event End')
                    ->required()
                    ->horizontal()
                    ->allowInput()
                    ->enableTime(),
                    
                Input::make('event_address')
                    ->title('Event Address')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. 2381 Ogilvie Rd')
                    ->horizontal(),
                    
                Input::make('event_zip_postal')
                    ->title('Event Zip/Postal')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. K1J 7N4')
                    ->horizontal(),
                    
                Select::make('venue_id')
                    ->title('Venue')
                    ->fromQuery(Vendors::query()->where('category_id', Categories::where('name', 'LIKE', '%'. 'Venue' . '%')->first()->id), 'company_name')
                    ->horizontal(),

                Select::make('school')
                    ->title('School')
                    ->required()
                    ->horizontal()
                    ->empty('Start typing to Search...')
                    ->fromModel(School::class, 'school_name', 'school_name'),

                Select::make('country')
                    ->title('Country')
                    ->required()
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country'),

                Select::make('county')
                    ->title('County')
                    ->required()
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'county', 'county'),

                Select::make('state_province')
                    ->title('State/Province')
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'state_province', 'state_province'),

                TextArea::make('event_info')
                    ->title('Event Info')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. Formal Attire')
                    ->horizontal()
                    ->rows(5),

                TextArea::make('event_rules')
                    ->title('Event Rules')
                    ->required()
                    ->placeholder('Ex. No Violence')
                    ->horizontal()
                    ->rows(5),
                
                Input::make('ticket_price')
                    ->title('Ticket Price $')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. 29.99')
                    ->horizontal(), 

     
                Input::make('capacity')
                    ->title('Event Capacity')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. 100')
                    ->horizontal(), 

                Select::make('interested_vendor_categories')
                    ->title('Interested Vendor Categories')
                    ->fromModel(Categories::class, 'name')
                    ->horizontal()
                    ->multiple()
                    ->help('Vendors from this category will be able to place bids on the event.'),
            ]),
        ];
    }

    public function createEvent(Request $request){

        try{

            $school_id = School::where('school_name', $request->input('school'))
                                ->where('county', $request->input('county'))
                                ->where('state_province', $request->input('state_province'))
                                ->where('country', $request->input('country'))
                                ->get('id')->value('id');

            if(is_null($school_id)){
                throw New Exception('You are trying to enter a invalid school');
            }

            $validator = Validator::make($request->all(), [
                'event_name' => 'required|max:255',
                'event_start_time' => 'required|date',
                'event_finish_time' => 'required|date|after_or_equal:event_start_time',
                'event_info' => 'nullable|max:429496729',
                'event_rules' => 'nullable|max:429496729',
                'event_address' => 'nullable|max:429496729',
                'event_zip_postal' => 'nullable|max:2147483647',
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
                'ticket_price' => 'required|numeric|gte:0',
                'capacity' => 'required|integer|max:4294967295|gte:0',
                'interested_vendor_categories' => 'nullable|array',
                'interested_vendor_categories.*' => Rule::in(Categories::all()->pluck('id')),
            ],
            $messages = [
                'interested_vendor_categories.*.in' => 'The interested vendor categories are invalid.'
            ]);

            $formFields = $validator->validated();
            // dd($formFields);
            $formFields['event_creator'] = auth()->id();
            $formFields['school_id'] = $school_id;

            Events::create($formFields);

            Toast::success('Event Added Succesfully');
            
            return redirect()->route('platform.event.list');

        }catch(Exception $e){
            
            Alert::error('There was an error creating this event. Error Code: ' . $e->getMessage());
        }
    }
}

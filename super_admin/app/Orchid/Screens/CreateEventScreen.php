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
    public $event;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event, Request $request): iterable
    {
        $event->event_name = $request->input('event_name') ?? "";
        $event->event_start_time = $request->input('event_start_time') ?? "";
        $event->event_finish_time = $request->input('event_finish_time') ?? "";
        $event->event_address = $request->input('event_address') ?? "";
        $event->event_zip_postal = $request->input('event_zip_postal') ?? "";
        $event->venue_id = $request->input('venue_id') ?? "";
        $event->school = $request->input('school') ?? "";
        $event->country = $request->input('country') ?? "";
        $event->county = $request->input('county') ?? "";
        $event->state_province = $request->input('state_province') ?? "";
        $event->event_info = $request->input('event_info') ?? "";
        $event->event_rules = $request->input('event_rules') ?? "";
        $event->ticket_price = $request->input('ticket_price') ?? "";
        $event->capacity = $request->input('capacity') ?? "";

        if($request->input('interested_vendor_categories') !== null) {
            $event->interested_vendor_categories =  array_values(array_map('intval', $request->input('interested_vendor_categories')));
        }else{
            $event->interested_vendor_categories =null;
        }

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
                    ->horizontal()
                    ->value($this->event->event_name),

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


                Input::make('event_address')
                    ->title('Event Address')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. 2381 Ogilvie Rd')
                    ->horizontal()
                    ->value($this->event->event_address),

                Input::make('event_zip_postal')
                    ->title('Event Zip/Postal')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. K1J 7N4')
                    ->horizontal()
                    ->value($this->event->event_zip_postal),

                Select::make('venue_id')
                    ->title('Venue')
                    ->fromQuery(Vendors::query()->where('category_id', Categories::where('name', 'LIKE', '%'. 'Venue' . '%')->first()->id), 'company_name')
                    ->horizontal()
                    ->value($this->event->venue_id),

                Select::make('school')
                    ->title('School')
                    ->required()
                    ->horizontal()
                    ->empty('Start typing to Search...')
                    ->fromModel(School::class, 'school_name', 'school_name')
                    ->value($this->event->school),

                Select::make('country')
                    ->title('Country')
                    ->required()
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country')
                    ->value($this->event->country),

                Select::make('county')
                    ->title('County')
                    ->required()
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'county', 'county')
                    ->value($this->event->county),

                Select::make('state_province')
                    ->title('State/Province')
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'state_province', 'state_province')
                    ->value($this->event->state_province),

                TextArea::make('event_info')
                    ->title('Event Info')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. Formal Attire')
                    ->horizontal()
                    ->rows(5)
                    ->value($this->event->event_info),

                TextArea::make('event_rules')
                    ->title('Event Rules')
                    ->required()
                    ->placeholder('Ex. No Violence')
                    ->horizontal()
                    ->rows(5)
                    ->value($this->event->event_rules),

                Input::make('ticket_price')
                    ->title('Ticket Price $')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. 29.99')
                    ->horizontal()
                    ->value($this->event->ticket_price),


                Input::make('capacity')
                    ->title('Event Capacity')
                    ->type('text')
                    ->required()
                    ->placeholder('Ex. 100')
                    ->horizontal()
                    ->value($this->event->capacity),

                Select::make('interested_vendor_categories')
                    ->title('Interested Vendor Categories')
                    ->fromModel(Categories::class, 'name')
                    ->horizontal()
                    ->multiple()
                    ->help('Vendors from this category will be able to place bids on the event.')
                    ->value($this->event->interested_vendor_categories),
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

            Toast::success('Event Added Successfully');

            return redirect()->route('platform.event.list');

        }catch(Exception $e){

            Alert::error('There was an error creating this event. Error Code: ' . $e->getMessage());
            return redirect()->route('platform.event.create', request(['event_name', 'event_start_time', 'event_finish_time', 'event_address', 'event_zip_postal',  'venue_id', 'school', 'country', 'county', 'state_province', 'event_info', 'event_rules', 'ticket_price', 'capacity', 'interested_vendor_categories']));

        }
    }
}

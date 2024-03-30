<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\School;
use App\Models\Vendors;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
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
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\DateTimer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\ModalToggle;

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
                    ->placeholder('Colonel By\'s Main Event')
                    ->horizontal(),

                DateTimer::make('event_start_time')
                    ->title('Event Start')
                    ->horizontal()
                    ->allowInput()
                    ->required()
                    ->enableTime(),

                DateTimer::make('event_finish_time')
                    ->title('Event End')
                    ->horizontal()
                    ->allowInput()
                    ->required()
                    ->enableTime(),

                TextArea::make('event_info')
                    ->title('Event Info')
                    ->type('text')
                    ->placeholder('Ex. Formal Attire')
                    ->horizontal()
                    ->rows(5),

                TextArea::make('event_rules')
                    ->title('Event Rules')
                    ->type('text')
                    ->placeholder('Ex. No Violence')
                    ->horizontal()
                    ->rows(5),

                Select::make('venue_id')
                    ->title('Venue')
                    ->fromQuery(Vendors::query()->where('category_id', Categories::where('name', 'LIKE', '%'. 'Venue' . '%')->first()->id), 'company_name')
                    ->empty('Start typing to Search...')
                    ->horizontal(),
                
                Input::make('ticket_price')
                    ->title('Ticket Price $')
                    ->type('text')
                    ->required()
                    ->placeholder('29.99')
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

                ModalToggle::make('Suggest Category')
                    ->modal('suggestCategoryModal')
                    ->method('suggestCategory')
                    ->icon('plus')
                    ->class('btn btn-default mb-3'),
                
                Button::make('Create Event')
                // ->icon('plus')
                ->type(Color::PRIMARY())
                ->method('createEvent'),
                
                    
            ])->title('Make your dream event'),
        ];
    }

    public function createEvent(Request $request){

        $school = School::where('id', Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id'))->first();

        try{
            $validator = Validator::make($request->all(), [
                'event_name' => 'required|max:255',
                'event_start_time' => 'required|date',
                'event_finish_time' => 'required|date|after_or_equal:event_start_time',
                'event_info' => 'nullable|max:429496729',
                'event_rules' => 'nullable|max:429496729',
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
            $formFields['event_creator'] = auth()->id();
            $formFields['school_id'] = $school->id;
            $formFields['school'] = $school->school_name;
            $formFields['region_id'] = $school->region_id;

            Events::create($formFields);

            Toast::success('Event Added Succesfully');
            
            return redirect()->route('platform.event.list');

        }catch(Exception $e){
            
            Alert::error('There was an error creating this event. Error Code: ' . $e->getMessage());
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

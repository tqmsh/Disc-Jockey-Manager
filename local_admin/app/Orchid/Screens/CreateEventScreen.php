<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\School;
use App\Models\Venues;
use App\Models\Clients;
use App\Models\Staffs;
use App\Models\Systems;
use App\Models\Song;
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
use Orchid\Screen\Fields\DateTimer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\ModalToggle;

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
        $event->event_date = $request->input('event_date') ?? "";
        $event->event_loadin_time = $request->input('event_loadin_time') ?? "";
        $event->event_start_time = $request->input('event_start_time') ?? "";
        $event->event_finish_time = $request->input('event_finish_time') ?? "";
        $event->venue_id = $request->input('venue_id') ?? "";
        $event->client_id = $request->input('client_id') ?? "";
        $event->staff_id = $request->input('staff_id') ?? "";
        $event->system_id = $request->input('system_id') ?? "";
        $event->song_ids = $request->input('song_ids') ?? [];

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
                    ->required()
                    ->horizontal()
                    ->value($this->event->client_id)
                    ->empty('Start typing to search...'),

                DateTimer::make('event_date')
                    ->title('Event Date')
                    ->required()
                    ->horizontal()
                    ->allowInput()
                    ->enableTime(false)
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

                Select::make('venue_id')
                    ->title('Venue')
                    ->required()
                    ->fromQuery(Venues::query(), 'name')
                    ->horizontal()
                    ->value($this->event->venue_id)
                    ->empty('Start typing to search...'),

                Select::make('staff_id')
                    ->title('Staff')
                    ->required()
                    ->fromQuery(Staffs::query(), 'full_name')
                    ->horizontal()
                    ->value($this->event->staff_id)
                    ->empty('Start typing to search...'),

                Select::make('system_id')
                    ->title('System')
                    ->required()
                    ->fromQuery(Systems::query(), 'full_name')
                    ->horizontal()
                    ->value($this->event->system_id)
                    ->empty('Start typing to search...'),

                Select::make('song_ids')
                    ->title('Songs')
                    ->required()
                    ->multiple()
                    ->fromQuery(Song::query(), 'title')
                    ->horizontal()
                    ->value($this->event->song_ids)
                    ->empty('Start typing to search...'),

                ModalToggle::make('Suggest Category')
                    ->modal('suggestCategoryModal')
                    ->method('suggestCategory')
                    ->icon('plus')
                    ->class('btn btn-default mb-3'),

                Button::make('Create Event')
                    ->type(Color::PRIMARY())
                    ->method('createEvent'),
            ])->title('Make your dream event'),
        ];
    }

    public function createEvent(Request $request){
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
                'interested_vendor_categories' => 'nullable|array',
                'interested_vendor_categories.*' => Rule::in(Categories::all()->pluck('id')),
            ]);

            $validated = $validator->validated();
            $validated['interested_vendor_categories'] = $validated['interested_vendor_categories'] ?? null;
            $validated['event_creator'] = auth()->id();

            Events::create($validated);

            Toast::success('Event Added Successfully');

            return redirect()->route('platform.event.list');

        } catch (Exception $e) {
            Alert::error('There was an error creating this event. Error Code: ' . $e->getMessage());
            return redirect()->route('platform.event.create', $request->except('_token'));
        }
    }


    public function suggestCategory()
    {
        $validator = Validator::make(request()->all(), [
            'category_name' => 'required|max:255|unique:categories,name',
        ]);
        Categories::create(['name' => $validator->validated()['category_name']]);
        Toast::success('Category suggested successfully');
    }
}

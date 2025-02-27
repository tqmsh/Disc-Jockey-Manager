<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use App\Models\EventsHistoricalRecord;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\TextArea;
use Illuminate\Support\Facades\Auth;

use App\Models\Localadmin;

class CreatePromHistoryScreen extends Screen
{
    public $event;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        $existingRecord = EventsHistoricalRecord::where('event_id', $event->id)->first();
        abort_if($existingRecord, 403, 'A historical record already exists for this event.');

        $localAdminSchoolId = Localadmin::where('user_id', Auth::id())->first()->school_id;
        abort_if($localAdminSchoolId != $event->school_id, 403, 'You are not authorized to view this page');

        return [
            'event' => $event,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Create Historical Record for: ' . $this->event->event_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create')
                ->icon('plus')
                ->type(Color::PRIMARY())
                ->method('createRecord'),
            

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list'),
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

                Input::make('venue_name')
                    ->title('Name')
                    ->type('text')
                    ->horizontal(),
                    // ->placeholder('Ex. John'),
                TextArea::make('venue_notes')
                    ->title('Notes')
                    ->rows(5)
                    ->horizontal(),
                    // ->placeholder('Ex. Doe'),
            ])->title('Venue'),

            Layout::rows([

                Input::make('disc_jockey_name')
                    ->title('Name')
                    ->type('text')
                    ->horizontal(),
                    // ->placeholder('Ex. John'),
                TextArea::make('disc_jockey_notes')
                    ->title('Notes')
                    ->rows(5)
                    ->horizontal(),
                    // ->placeholder('Ex. Doe'),

            ])->title('Disc Jockey'),

            Layout::rows([

                Input::make('photobooth_name')
                    ->title('Name')
                    ->type('text')
                    ->horizontal(),
                    // ->placeholder('Ex. John'),
                TextArea::make('photobooth_notes')
                    ->title('Notes')
                    ->rows(5)
                    ->horizontal(),
                    // ->placeholder('Ex. Doe'),

            ])->title('Photobooth'),

            Layout::rows([

                Input::make('photographer_name')
                    ->title('Name')
                    ->type('text')
                    ->horizontal(),
                    // ->placeholder('Ex. John'),
                TextArea::make('photographer_notes')
                    ->title('Notes')
                    ->rows(5)
                    ->horizontal(),
                    // ->placeholder('Ex. Doe'),

            ])->title('Photographer'),

            Layout::rows([

                Input::make('videographer_name')
                    ->title('Name')
                    ->type('text')
                    ->horizontal(),
                    // ->placeholder('Ex. John'),
                TextArea::make('videographer_notes')
                    ->title('Notes')
                    ->rows(5)
                    ->horizontal(),
                    // ->placeholder('Ex. Doe'),

            ])->title('Videographer'),
            
        ];
    }


    public function createRecord(Request $request, Events $event)
    {
        try {
            $sections = [
                'Venue' => ['venue_name', 'venue_notes'],
                'Disc Jockey' => ['disc_jockey_name', 'disc_jockey_notes'],
                'Photobooth' => ['photobooth_name', 'photobooth_notes'],
                'Photographer' => ['photographer_name', 'photographer_notes'],
                'Videographer' => ['videographer_name', 'videographer_notes'],
            ];

            $completedSections = 0;

            foreach ($sections as $sectionTitle => $sectionFields) {
                $sectionValues = array_map(function ($field) use ($request) {
                    return $request->input($field);
                }, $sectionFields);

                $sectionValues = array_filter($sectionValues, function ($value) {
                    return !empty($value);
                });

                if (count($sectionValues) === 0) {
                    continue;
                }

                if (count($sectionValues) !== count($sectionFields)) {
                    Alert::error('Please fill in both inputs of the ' . $sectionTitle . ' section.');
                    return redirect()->back();
                }

                $completedSections++;
            }

            if ($completedSections === 0) {
                Alert::error('Please complete at least one section.');
                return redirect()->back();
            }
            

            $positionField = $request->all();
            $positionField['event_id'] = $event->id;

            EventsHistoricalRecord::create($positionField);
            Toast::success('Record Created Successfully');
            return redirect()->route('platform.event.list');
        } catch (Exception $e) {
            Alert::error('There was an error creating this Historical Record. Error Code: ' . $e->getMessage());
        }
    }
}


// Add the edit button and the edit screen

<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use App\Models\HistoricalRecord;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\TextArea;

use Illuminate\Support\Facades\Auth;
use App\Models\Localadmin;

class EditPromHistoryScreen extends Screen
{
    public $event;
    public $record;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        $existingRecord = HistoricalRecord::where('event_id', $event->id)->first();
        abort_if(!$existingRecord, 403, 'A historical record does not exist for this event.');

        $localAdminSchoolId = Localadmin::where('user_id', Auth::id())->first()->school_id;
        abort_if($localAdminSchoolId != $event->school_id, 403, 'You are not authorized to view this page');

        return [
            'event' => $event,
            'record' => $existingRecord,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Historical Record for: ' . $this->event->event_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('check')
                ->type(Color::SUCCESS())
                ->method('update', [$this->record]),
            

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
                    ->horizontal()
                    ->value($this->record->venue_name),
                    // ->placeholder('Ex. John'),
                TextArea::make('venue_notes')
                    ->title('Notes')
                    ->rows(5)
                    ->horizontal()
                    ->value($this->record->venue_notes),
                    // ->placeholder('Ex. Doe'),
            ])->title('Venue'),

            Layout::rows([

                Input::make('disk_jockey_name')
                    ->title('Name')
                    ->type('text')
                    ->horizontal()
                    ->value($this->record->disk_jockey_name),
                    // ->placeholder('Ex. John'),
                TextArea::make('disk_jockey_notes')
                    ->title('Notes')
                    ->rows(5)
                    ->horizontal()
                    ->value($this->record->disk_jockey_notes),
                    // ->placeholder('Ex. Doe'),

            ])->title('Disc Jockey'),

            Layout::rows([

                Input::make('photobooth_name')
                    ->title('Name')
                    ->type('text')
                    ->horizontal()
                    ->value($this->record->photobooth_name),
                    // ->placeholder('Ex. John'),
                TextArea::make('photobooth_notes')
                    ->title('Notes')
                    ->rows(5)
                    ->horizontal()
                    ->value($this->record->photobooth_notes),
                    // ->placeholder('Ex. Doe'),

            ])->title('Photobooth'),

            Layout::rows([

                Input::make('photographer_name')
                    ->title('Name')
                    ->type('text')
                    ->horizontal()
                    ->value($this->record->photographer_name),
                    // ->placeholder('Ex. John'),
                TextArea::make('photographer_notes')
                    ->title('Notes')
                    ->rows(5)
                    ->horizontal()
                    ->value($this->record->photographer_notes),
                    // ->placeholder('Ex. Doe'),

            ])->title('Photographer'),

            Layout::rows([

                Input::make('videographer_name')
                    ->title('Name')
                    ->type('text')
                    ->horizontal()
                    ->value($this->record->videographer_name),
                    // ->placeholder('Ex. John'),
                TextArea::make('videographer_notes')
                    ->title('Notes')
                    ->rows(5)
                    ->horizontal()
                    ->value($this->record->videographer_notes),
                    // ->placeholder('Ex. Doe'),

            ])->title('Videographer'),
            
        ];
    }

    public function update(Request $request, $record)
    {
        try{
            $sections = [
            'Venue' => ['venue_name', 'venue_notes'],
            'Disc Jockey' => ['disk_jockey_name', 'disk_jockey_notes'],
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
                Toast::error('Please fill in both inputs of the ' . $sectionTitle . ' section.');
                return redirect()->back();
            }

            $completedSections++;
        }

        if ($completedSections === 0) {
            Toast::error('Please complete at least one section.');
            return redirect()->back();
        }

        $historicalRecord = HistoricalRecord::where('event_id', $record)->first();
        $historicalRecord->update($request->all());

        Toast::success('You have successfully updated this Historical Record.');
        return redirect()->route('platform.event.list');

        }catch(Exception $e){
            Alert::error('There was an error editing this student. Error Code: ' . $e->getMessage());
        }
    } 
}
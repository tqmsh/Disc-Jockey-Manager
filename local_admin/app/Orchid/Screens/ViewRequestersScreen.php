<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Song;
use App\Models\Events;
use App\Models\Student;
use Orchid\Screen\Screen;
use App\Models\SongRequest;
use Illuminate\Http\Request;
use App\Models\EventAttendees;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\ModalToggle;
use App\Orchid\Layouts\ViewRequestersLayout;

class ViewRequestersScreen extends Screen
{

    public Events $event;
    public Song $song;
    public array $requesters;

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Requesters for: ' . $this->song->title . ' by ' . $this->song->artists;
    }

    public string $description = "View or change the users that have requested this song.";

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Song $song, Events $event): iterable
    {
        $songRequests = SongRequest::where('song_id', $song->id)
            ->where('event_id', $event->id)
            ->get();

        $requesters = [];
        foreach ($songRequests as $songRequest) {
            $requesters[] = $songRequest->user_id;
        }

        return [
            'event' => $event,
            'song' => $song,
            'requesters' => $requesters,
        ];
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
                ->route('platform.songreq.list', $this->event->id),

            ModalToggle::make('Add Requester')
                ->modal('reqModal')
                ->method('create')
                ->icon('plus'),

            Button::make('Delete Selected Requesters')
                ->icon('trash')
                ->confirm('Are you sure you want to delete the selected requesters?')
                ->method('delete'),

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
            Layout::modal('reqModal', [
                Layout::rows([
                    Select::make('student.id')
                        ->options(function () {
                            $arr = array();
                            foreach (Student::where('school_id', $this->event->school_id)->get() as $student) {
                                if (EventAttendees::where('user_id', $student->user_id)->where('event_id', $this->event->id)->exists() && !in_array($student->user_id, $this->requesters)) {
                                    $arr[$student->user_id] = 'ID: ' . $student->user_id . ', Name: ' . $student->firstname . ' ' . $student->lastname;
                                }
                            }
                            return $arr;
                        })
                        ->empty('Add a student')
                        ->required('You must select a student.')
                ]),
            ])
                ->title('Create Requester')
                ->applyButton('Add'),

            ViewRequestersLayout::class

        ];
    }

    public function create(Request $request, Song $song, Events $event)
    {
        try {
            $student_id = $request->input('student.id');

            $songRequest = new SongRequest([
                'user_id' => $student_id,
                'song_id' => $song->id,
                'event_id' => $event->id,
            ]);

            $songRequest->save();

            Toast::success("Song request created");
        } catch (Exception) {
            Toast::error("An error has occurred, try again later");
        }
    }

    public function delete(Request $request, Song $song, Events $event)
    {
        $reqList = $request->get('requesterList');

        if (empty($reqList)) {
            Toast::error("You have not selected anything to delete");
        } else {
            SongRequest::where('song_id', $song->id)
                ->where('event_id', $event->id)
                ->whereIn('user_id', $reqList)
                ->delete();
            Toast::success("Selected requesters successfully deleted");
        }
    }

}

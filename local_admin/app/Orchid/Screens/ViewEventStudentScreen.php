<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use App\Models\Student;
use Orchid\Screen\Screen;
use App\Models\EventAttendees;
use App\Orchid\Layouts\ViewStudentLayout;

class ViewEventStudentScreen extends Screen
{
    public $event;
    public $students;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        return [
            'event' => $event,
            'students' => Student::whereIn('user_id', EventAttendees::where('event_id', $event->id)->get(['user_id']))->paginate(20)

        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Students in: ' . $this->event->event_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ViewStudentLayout::class
        ];
    }
}

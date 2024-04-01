<?php

namespace App\Orchid\Layouts;

use App\Models\EventAttendees;
use App\Models\Events;
use Orchid\Screen\TD;
use App\Models\Region;
use App\Models\Student;
use Carbon\Carbon;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;

class ViewStudentBidLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'students';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [

            TD::make()
                ->align(TD::ALIGN_RIGHT)
                ->render(function($event){
                    return Button::make('Place Bid')->type(Color::PRIMARY())->method('redirect', ['student_id' => $event->id, 'type' => 'student'])->icon('plus');
                }), 

            TD::make('username', 'Username')
                ->render(function (Student $student) {
                    return e($student->user->name);
                }),
            TD::make('school', 'School')
                ->render(function (Student $student) {
                    return e($student->school);
                })->width('225px'),
            TD::make('gender', 'Gender')
                ->render(function (Student $student) {
                    return e(ucwords($student->specs->gender));
                }),
            TD::make('next_event_start', 'Next Attending Event Start')
                ->render(function (Student $student) {
                    $now = Carbon::now();
                    $closestAttendingEvent = Events::whereIn('id',
                        EventAttendees::where('user_id', $student->user_id)->pluck('event_id')
                    )->where('event_start_time', '>', $now)->oldest('event_start_time')->first();
                    return !is_null($closestAttendingEvent) ? $closestAttendingEvent->event_start_time : 'N/A';
                }),
            TD::make('interested_vendor_categories', 'Interested Categories')
                ->render(function($event){
                    return e($event->getInterestedCategoriesNames());
                })->defaultHidden(),
        ];
    }
}

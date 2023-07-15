<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Region;
use App\Models\Student;
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

            TD::make('firstname', 'First Name')
                ->render(function (Student $student) {
                    return e($student->firstname);
                }),
            TD::make('lastname', 'Last Name')
                ->render(function (Student $student) {
                    return e($student->lastname);
                }),
            TD::make('email', 'Email')
                ->render(function (Student $student) {
                    return e($student->email);
                }),
            TD::make('region', 'Region')
                ->render(function (Student $student) {
                    return e(Region::find($student->school()->first()->region_id)->name);
                }),
            TD::make('school', 'School')
                ->render(function (Student $student) {
                    return e($student->school);
                })->width('225px'),
            TD::make('grade', 'Grade')
                ->render(function (Student $student) {
                    return e($student->grade);
                }),
            TD::make('allergies', 'Allergies')
                ->render(function (Student $student) {
                    return e($student->allergies);
                }),
        ];
    }
}

<?php

namespace App\Orchid\Layouts;

use App\Models\User;
use Orchid\Screen\TD;
use App\Models\Student;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ViewStudentLayout extends Table
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
                ->render(function (Student $student){
                    return CheckBox::make('students[]')
                        ->value($student->user_id)
                        ->checked(false);
                }),

            TD::make('firstname', 'First Name')
                ->render(function (Student $student) {
                    return Link::make($student->firstname)
                        ->route('platform.student.edit', $student);
                }),
            TD::make('lastname', 'Last Name')
                ->render(function (Student $student) {
                    return Link::make($student->lastname)
                        ->route('platform.student.edit', $student);
                }),
            TD::make('email', 'Email')
                ->render(function (Student $student) {
                    return Link::make($student->email)
                        ->route('platform.student.edit', $student);
                }),
            TD::make('country', 'Country')
                ->render(function (Student $student) {
                    return Link::make(User::find($student->user_id)->country)
                        ->route('platform.student.edit', $student);
                }),
            TD::make('school', 'School')
                ->render(function (Student $student) {
                    return Link::make($student->school)
                        ->route('platform.student.edit', $student);
                }),
            TD::make('grade', 'Grade')
                ->render(function (Student $student) {
                    return Link::make($student->grade)
                        ->route('platform.student.edit', $student);
                }),
            TD::make('phonenumber', 'Phone Number')
                ->render(function (Student $student) {
                    return Link::make($student->phonenumber)
                        ->route('platform.student.edit', $student);
                }),
            TD::make('allergies', 'Allergies')
                ->render(function (Student $student) {
                    return Link::make($student->allergies)
                        ->route('platform.student.edit', $student);
                }),

                
            TD::make()
                ->render(function (Student $student) {
                    return Button::make('Edit')-> type(Color::PRIMARY())->  method('redirect', ['student'=> $student-> id]) ->icon('pencil');
                }),
        ];
    }
}

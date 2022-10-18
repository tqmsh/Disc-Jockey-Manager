<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\School;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;

class ViewSchoolLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'schools';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            
            TD::make()
                ->render(function (School $school){
                    return CheckBox::make('schools[]')
                        ->value($school->id)
                        ->checked(false);
                }),
            TD::make('school_name', 'School Name')
                ->render(function (School $school) {
                    return Link::make($school->school_name)
                        ->route('platform.school.edit', $school);
                }),
            TD::make('country', 'Country')
                ->render(function (School $school) {
                    return Link::make($school->country)
                        ->route('platform.school.edit', $school);
                }),
            TD::make('state_province', 'State/Province')
                ->render(function (School $school) {
                    return Link::make($school->state_province)
                        ->route('platform.school.edit', $school);
                }),
            TD::make('teacher', 'Teacher')
                ->render(function (School $school) {
                    return Link::make($school->teacher_name)
                        ->route('platform.school.edit', $school);
                }),
            TD::make('teacher_email', 'Teacher Email')
                ->render(function (School $school) {
                    return Link::make($school->teacher_email)
                        ->route('platform.school.edit', $school);
                }),
            TD::make('teacher_cell', 'Teacher Cell')
                ->render(function (School $school) {
                    return Link::make($school->teacher_cell)
                        ->route('platform.school.edit', $school);
                }),
            TD::make('address', 'Address')
                ->render(function (School $school) {
                    return Link::make($school->address)
                        ->route('platform.school.edit', $school);
                }),
        ];
    }
}

<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Region;
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

            TD::make('school_board', 'School Board')
                ->render(function (School $school) {
                    return Link::make($school->school_board)
                        ->route('platform.school.edit', $school);
                }),

            TD::make('country', 'Country')
                ->render(function (School $school) {
                    return Link::make($school->country)
                        ->route('platform.school.edit', $school);
                }),
                
            TD::make('region_id', 'Region')
                ->render(function (School $school) {
                    return Link::make(Region::find($school->region_id)->name)
                        ->route('platform.school.edit', $school);
                }),

            TD::make('state_province', 'State/Province')
                ->render(function (School $school) {
                    return Link::make($school->state_province)
                        ->route('platform.school.edit', $school);
                }),

            TD::make('county', 'County')
                ->render(function (School $school) {
                    return Link::make($school->county)
                        ->route('platform.school.edit', $school);
                }),

            TD::make('total_students', 'Total Students')
                ->render(function (School $school) {
                    return Link::make($school->total_students)
                        ->route('platform.school.edit', $school);
                }),
        ];
    }
}

<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Section;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;

class ViewCourseSectionLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'sections';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
          TD::make()
                ->render(function (Section $section){
                    return CheckBox::make('sections[]')
                        ->value($section->id)
                        ->checked(false);
                }),

            TD::make('ordering', 'Order')
                ->render(function (Section $section) {
                    return e($section->ordering);
                }),
            TD::make('Section_name', 'Section Name')
                ->render(function (Section $section) {
                    return e($section->section_name);
                }),

            TD::make('created_at', 'Created At')
                ->render(function (Section $section) {
                    return e($section->created_at);
                }),

            TD::make('updated_at', 'Updated At')
                ->render(function (Section $section) {
                    return e($section->updated_at);
                }),

            TD::make()
                ->render(function (Section $section) {
                    return Button::make('Lessons')-> type(Color::SUCCESS())->method('redirect', ['section_id'=> $section->id, 'type' => 'section'])->icon('pencil');
                })->width('100px'),
            TD::make()
                ->render(function (Section $section) {
                    return Button::make('Edit')-> type(Color::PRIMARY())->method('redirect', ['section_id'=> $section->id, 'type' => 'edit'])->icon('pencil');
                }),
        ];
    }
}

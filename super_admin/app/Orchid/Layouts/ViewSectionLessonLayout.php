<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Lesson;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;

class ViewSectionLessonLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'lessons';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
          TD::make()
                ->render(function (Lesson $lesson){
                    return CheckBox::make('lessons[]')
                        ->value($lesson->id)
                        ->checked(false);
                }),

            TD::make('ordering', 'Order')
                ->render(function (Lesson $lesson) {
                    return e($lesson->ordering);
                }),

            TD::make('lesson_name', 'Lesson Name')
                ->render(function (Lesson $lesson) {
                    return e($lesson->lesson_name);
                }),

            TD::make('lesson_description', 'Lesson Description')
                ->render(function (Lesson $lesson) {
                    return e($lesson->lesson_description);
                })->width('20%'),

            TD::make('created_at', 'Created At')
                ->render(function (Lesson $lesson) {
                    return e($lesson->created_at);
                })->width('10%'),

            TD::make('updated_at', 'Updated At')
                ->render(function (Lesson $lesson) {
                    return e($lesson->updated_at);
                })->width('10%'),
                
            TD::make()
                ->render(function (Lesson $lesson) {
                    return Button::make('View')-> type(Color::SUCCESS())->method('redirect', ['lesson_id'=> $lesson->id, 'type' => 'view'])->icon('pencil');
                })->width('100px'),
            TD::make()
                ->render(function (Lesson $lesson) {
                    return Button::make('Edit')-> type(Color::PRIMARY())->method('redirect', ['lesson_id'=> $lesson->id, 'type' => 'edit'])->icon('pencil');
                }),
        ];
    }
}

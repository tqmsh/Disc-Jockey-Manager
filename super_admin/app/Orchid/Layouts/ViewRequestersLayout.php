<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Student;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ViewRequestersLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'requesters';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [

        TD::make()
            ->render(function ($requester ){
                return CheckBox::make('requesterList[]')
                    ->value($requester)
                    ->checked(false);  
            }),

        TD::make('requester_id', 'Requester User ID')
            ->render(function ($requester) {
                return e($requester);
            }), 

        TD::make('requester_name', 'Requester Name')
            ->render(function ($requester) {
                return e(Student::where('user_id', $requester)-> first() -> firstname . " " . Student::where('user_id', $requester)-> first() -> lastname);
            }), 

            
        ];
    }
}
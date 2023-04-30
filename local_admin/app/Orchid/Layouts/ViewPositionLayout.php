<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Election;
use App\Models\Position;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ViewPositionLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'position';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('position_name', 'Position Name')
                ->render(function (Position $position) {
                    return Link::make($position->position_name)
                        ->route('platform.eventPromvotePosition.edit',$position);
            }),
            TD::make()
                ->render(function($position){
                    return Button::make('Edit')->icon('pencil')->type(Color::PRIMARY())
                        ->method('redirect',['position' =>$position->id, 'type'=> "edit"]);
            }), 
            TD::make()
                ->render(function($position){
                    return Button::make('Delete')->icon('trash')->type(Color::DARK())
                        ->method('deletePosition',['position' =>$position->id])
                        ->confirm(__('Are you sure you want to delete position?'));
            }),
        ];
    }
}

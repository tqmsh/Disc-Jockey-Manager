<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Guide;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;

class ViewGuideLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'guides';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
          TD::make()
                ->render(function (Guide $guide){
                    return CheckBox::make('guides[]')
                        ->value($guide->id)
                        ->checked(false);
                }),

            TD::make('ordering', 'Order')
                ->render(function (Guide $guide) {
                    return Link::make($guide->ordering)
                        ->route('platform.guideSection.list', $guide);
                }),
            TD::make('guide_name', 'Guide Name')
                ->render(function (Guide $guide) {
                    return Link::make($guide->guide_name)
                        ->route('platform.guideSection.list', $guide);
                }),
            TD::make('category', 'Category')
                ->render(function (Guide $guide) {
                    return 
                        ($guide->category == 2) ? 'Committee' 
                        : (($guide->category == 3) ? 'Student' 
                        : (($guide->category == 4) ? 'Vendor' 
                        : 'Other'));                   

                }),

            TD::make('created_at', 'Created At')
                ->render(function (Guide $guide) {
                    return Link::make($guide->created_at)
                        ->route('platform.guideSection.list', $guide);
                }),

            TD::make('updated_at', 'Updated At')
                ->render(function (Guide $guide) {
                    return Link::make($guide->updated_at)
                        ->route('platform.guideSection.list', $guide);
                }),

            TD::make()
                ->render(function (Guide $guide) {
                    return Button::make('Sections')-> type(Color::SUCCESS())->method('redirect', ['guide'=> $guide->id, 'type' => 'section'])->icon('layers');
                }),
            TD::make()
                ->render(function (Guide $guide) {
                    return Button::make('Edit')-> type(Color::PRIMARY())->method('redirect', ['guide'=> $guide->id, 'type' => 'edit'])->icon('pencil');
                }),
        ];
    }
}

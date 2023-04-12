<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Categories;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ViewPendingCategoryLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'pending_categories';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            
            TD::make()
                ->render(function (Categories $category){
                    return CheckBox::make('pending_categories[]')
                        ->value($category->id)
                        ->checked(false);
                }),

            TD::make('name', 'Category Name')
                ->render(function (Categories $category) {
                    return Link::make($category->name)
                        ->route('platform.category.edit', $category);
                }),

            TD::make('created_at', 'Created At')
                ->render(function (Categories $category) {
                    return Link::make($category->created_at)
                        ->route('platform.category.edit', $category);
                }),
            TD::make('updated_at', 'Updated At')
                ->render(function (Categories $category) {
                    return Link::make($category->updated_at)
                        ->route('platform.category.edit', $category);
                }),

        ];
    }
}

<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;

use App\Models\User;
use Orchid\Screen\TD;
use App\Models\Vendors;
use App\Models\Categories;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;


use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewVendorCreditsLayout;


class ViewVendorCreditsScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'vendors' => Vendors::latest('vendors.created_at')->filter(request(['country', 'category_id', 'state_province']))->where('vendors.account_status', 1)->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Vendor Credits';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([

                Group::make([

                    Select::make('country')
                        ->title('Country')
                        ->empty('No Selection')
                        ->help('Type in boxes to search')
                        ->fromModel(Vendors::class, 'country', 'country'),

                    Select::make('category_id')
                        ->title('Category')
                        ->empty('No Selection')
                        ->fromQuery(Categories::query(), 'name')
                        ->placeholder('Select Category'),
                    
                    Select::make('state_province')
                        ->title('State/Province')
                        ->empty('No Selection')
                        ->fromModel(Vendors::class, 'state_province', 'state_province'),

                ]),
                
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),
            ViewVendorCreditsLayout::class,
        ];
    }

    public function redirect($vendor){
        return redirect()-> route('platform.vendor.payments', $vendor);
    }

    public function filter(){

        return redirect()->route('platform.vendor.credits', request(['country', 'category_id', 'state_province']));
    }
}

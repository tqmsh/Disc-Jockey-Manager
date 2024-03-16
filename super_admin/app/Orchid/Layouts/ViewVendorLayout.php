<?php

namespace App\Orchid\Layouts;

use App\Models\User;
use Orchid\Screen\TD;
use App\Models\Vendors;
use App\Models\Categories;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ViewVendorLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'vendors';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            
            TD::make()
                ->render(function (Vendors $vendor){
                    return CheckBox::make('vendors[]')
                        ->value($vendor->user_id)
                        ->checked(false);
                }),

            TD::make('company_name', 'Company')
                ->render(function (Vendors $vendor) {
                    return Link::make($vendor->company_name)
                        ->route('platform.vendor.edit', $vendor);
                }),
                
            TD::make('firstname', 'First Name')
                ->render(function (Vendors $vendor) {
                    return Link::make(User::find($vendor->user_id)->firstname)
                        ->route('platform.vendor.edit', $vendor);
                }),
                
            TD::make('lastname', 'Last Name')
                ->render(function (Vendors $vendor) {
                    return Link::make(User::find($vendor->user_id)->lastname)
                        ->route('platform.vendor.edit', $vendor);
                }),
                
            TD::make('email', 'Email')
                ->render(function (Vendors $vendor) {
                    $email = $vendor->email;

                    if (strlen($email) > 10) {
                        $email = substr($email, 0, 10) . '...';
                    }

                    return Link::make($email)
                        ->route('platform.vendor.edit', $vendor);
                }),

            TD::make('phone', 'Phone Number')
                ->render(function (Vendors $vendor) {
                    return Link::make($vendor->phonenumber)
                        ->route('platform.vendor.edit', $vendor);
                }),
                
            TD::make('category_id', 'Category')
                ->render(function (Vendors $vendor) {
                    return Link::make(Categories::find($vendor->category_id)->name)
                        ->route('platform.vendor.edit', $vendor);
                }),
                
                TD::make('website', 'Website')
                ->render(function (Vendors $vendor) {
                    $website = $vendor->website;

                    if (strpos($website, 'https://') === 0) {
                        $website = substr($website, 8);
                    }

                    if (strpos($website, 'www.') === 0) {
                        $website = substr($website, 4);
                    }
                    
                    if (strlen($website) > 15) {
                        $website = substr($website, 0, 15) . '...';
                    }
            
                    return Link::make($website)
                        ->href(($vendor->website) == null ? '#' : $vendor->website);
                }),
            
                

            // TD::make('country', 'Country')
            //     ->render(function (Vendors $vendor) {
            //         return Link::make($vendor->country)
            //             ->route('platform.vendor.edit', $vendor);
            //     }),

            // TD::make('state_province', 'State/Province')
            //     ->render(function (Vendors $vendor) {
            //         return Link::make($vendor->state_province)
            //             ->route('platform.vendor.edit', $vendor);
            //     }),

            // TD::make('city', 'City')
            //     ->render(function (Vendors $vendor) {
            //         return Link::make($vendor->city)
            //             ->route('platform.vendor.edit', $vendor);
            //     }),

            TD::make()
                ->render(function (Vendors $vendor) {
                    return Button::make('Edit')-> type(Color::PRIMARY())->  method('redirect', ['vendor'=>$vendor->id]) ->icon('pencil');
                }),
        ];
    }
}

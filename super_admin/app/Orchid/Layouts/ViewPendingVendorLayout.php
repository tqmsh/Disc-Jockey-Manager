<?php

namespace App\Orchid\Layouts;

use App\Models\User;
use Orchid\Screen\TD;
use App\Models\Vendors;
use App\Models\Catagories;
use App\Models\Categories;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;

class ViewPendingVendorLayout extends Table
{
    public $vendor;

    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'pending_vendors';

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

            TD::make('company_name', 'Company Name')
                ->render(function (Vendors $vendor) {
                    return Link::make($vendor->company_name)
                        ->route('platform.vendor.edit', $vendor);
                }),

            TD::make('category_id', 'Category')
                ->render(function (Vendors $vendor) {
                    return Link::make(Categories::find($vendor->category_id)->name)
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
                    return Link::make($vendor->email)
                        ->route('platform.vendor.edit', $vendor);
                }),

            TD::make('phone', 'Phone Number')
                ->render(function (Vendors $vendor) {
                    return Link::make($vendor->phonenumber)
                        ->route('platform.vendor.edit', $vendor);
                }),

           TD::make('website', 'Website')
                ->render(function (Vendors $vendor) {
                    return Link::make($vendor->website)
                        ->href(($vendor->website) == null ? '#' : $vendor->website);
                }),
                

            TD::make('country', 'Country')
                ->render(function (Vendors $vendor) {
                    return Link::make($vendor->country)
                        ->route('platform.vendor.edit', $vendor);
                }),

            TD::make('state_province', 'State/Province')
                ->render(function (Vendors $vendor) {
                    return Link::make($vendor->state_province)
                        ->route('platform.vendor.edit', $vendor);
                }),

            TD::make('city', 'City')
                ->render(function (Vendors $vendor) {
                    return Link::make($vendor->city)
                        ->route('platform.vendor.edit', $vendor);
                }),
        ];
    }
}

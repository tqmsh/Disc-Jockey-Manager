<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Region;
use App\Models\EventBids;
use Orchid\Support\Color;
use App\Models\Categories;
use App\Models\VendorPackage;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;

class ViewPastEventBidLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'pastBids';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make()
                ->render(function (EventBids $bid){
                    return CheckBox::make('bids[]')
                        ->value($bid->id)
                        ->checked(false);
                }),

            TD::make()
                ->align(TD::ALIGN_LEFT)
                ->width('125px')
                ->render(function(EventBids $bid){
                    return 
                        (($bid->status == 1) ? '<i class="text-success">●</i> Accepted' 
                        : '<i class="text-danger">●</i> Rejected');
                    }),  

            TD::make('company_name', 'Company')
                ->render(function($bid){
                    return Link::make($bid->company_name)
                        ->href($bid->url);
                }),

            TD::make('region_id', 'Region')
                ->render(function($bid){
                    return e(Region::find($bid->region_id)->name);

                }),

            TD::make('school_name', 'School')
                ->render(function($bid){
                    return e($bid->school_name);

                }),

            TD::make('category_id', 'Category')
                ->render(function($bid){
                    return e(Categories::find($bid->category_id)->name);
                }),

            TD::make('package_id', 'Package')
                ->render(function($bid){
                    return e(VendorPackage::find($bid->package_id)->package_name);
                }),

            TD::make('package_id', 'Description')
                ->width('300')
                ->render(function($bid){
                    return e(VendorPackage::find($bid->package_id)->description);
                }),

            TD::make('package_id', 'Price - $USD')
                ->width('110')
                ->align(TD::ALIGN_CENTER)
                ->render(function($bid){
                    return e('$' . number_format(VendorPackage::find($bid->package_id)->price));
                }),

            TD::make('package_id', 'Package URL')
                ->width('200')
                ->render(function($bid){
                    return Link::make(VendorPackage::find($bid->package_id)->url)->href(VendorPackage::find($bid->package_id)->url);
                }),

          TD::make('notes', 'Vendor Notes')
                ->width('300')
                ->render(function($bid){
                    return e($bid->notes);
                }),

          TD::make('contact_instructions', 'Contact Info')
                ->width('300')
                ->render(function($bid){
                       return e($bid->contact_instructions);

                }),
        ];
    }
}
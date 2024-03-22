<?php

namespace App\Orchid\Layouts;

use App\Models\BeautyGroupBid;
use Orchid\Screen\TD;
use App\Models\Categories;
use App\Models\LimoGroupBid;
use App\Models\VendorPackage;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class ViewBeautyBidsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'previousBeautyBids';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make()
                ->align(TD::ALIGN_LEFT)
                ->width('125px')
                ->render(function(BeautyGroupBid $bid){
                    return 
                        (($bid->status == 1) ? '<i class="text-success">●</i> Interested' 
                        : '<i class="text-danger">●</i> Not Interested');
                    }),  

            TD::make('company_name', 'Company')
                ->render(function($bid){
                    return Link::make($bid->company_name)
                        ->route('platform.studentBids.beautyGroup.view', $bid)
                        ->target('_blank');
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
                    return Link::make(VendorPackage::find($bid->package_id)->url)
                        ->route('platform.studentBids.beautyGroup.view', $bid)
                        ->target('_blank');
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

<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\StudentBids;
use App\Models\Categories;
use App\Models\VendorPackage;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class ViewStudentBidsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'previousBids';


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
                ->width('150px')
                ->render(function(StudentBids $bid){
                    return 
                        (($bid->status == 1) ? '<i class="text-success">●</i> Interested' 
                        : '<i class="text-danger">●</i> Not Interested');
                    }),  

            TD::make('company_name', 'Company')
                ->render(function($bid){
                    return Link::make($bid->company_name)
                        ->route('platform.studentBids.studentBid.view', $bid)
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
                })->defaultHidden(),

            TD::make('package_id', 'Price - $USD')
                ->width('110')
                ->align(TD::ALIGN_CENTER)
                ->render(function($bid){
                    return e('$' . number_format(VendorPackage::find($bid->package_id)->price));
                })->defaultHidden(),

            TD::make('package_id', 'Package URL')
                ->width('200')
                ->render(function($bid){
                    return Link::make(VendorPackage::find($bid->package_id)->url)
                        ->route('platform.studentBids.studentBid.view', $bid)
                        ->target('_blank');
                })->defaultHidden(),

          TD::make('notes', 'Vendor Notes')
                ->width('300')
                ->render(function($bid){
                    return e($bid->notes);
                })->defaultHidden(),

          TD::make('contact_instructions', 'Contact Info')
                ->width('300')
                ->render(function($bid){
                       return e($bid->contact_instructions);

                })->defaultHidden(),
        ];    
    }
}

<?php

namespace App\Orchid\Layouts;

use App\Models\VendorPackage;
use Orchid\Screen\Layouts\Legend;
use Orchid\Screen\Sight;

class ViewDetailedBidLayout extends Legend
{

    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'bid';

    /**
     * @return Sight[]
     */
    protected function columns() : array {
        return [
            Sight::make('status', 'Status')
                ->render(function($bid) {
                    return match($bid->status) {
                        0 => '<i>●</i> Pending',
                        1 => '<i class="text-success">●</i> Interested',
                        2 => '<i class="text-danger">●</i> Not Interested'
                    };
                }),

            Sight::make('company_name', 'Company Name')
                ->render(function($bid) {
                    return '<a style="text-decoration:underline;" href="' . $bid->url . '" target="_blank">' . $bid->company_name . '</a>';
                }),
            
            Sight::make('package_name', 'Package Name')
                ->render(function($bid) {
                    $package = VendorPackage::find($bid->package_id);
                    return '<a style="text-decoration:underline;" href="' . $package->url . '" target="_blank">' . $package->package_name . '</a>';
                }),
            
            Sight::make('package_price', 'Package Price - $USD')
                ->render(function($bid) {
                    return '$' . number_format(VendorPackage::find($bid->package_id)->price);
                }),
            
            Sight::make('package_description', 'Package Description')
                ->render(function($bid) {
                    return VendorPackage::find($bid->package_id)->description;
                }),
                
            Sight::make('notes', 'Bid Notes'),

            Sight::make('contact_instructions', 'Contact Instructions'),
        ];
    }
    
}


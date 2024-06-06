<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\VendorPackage;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Support\Color;

class ViewPackageLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'packages';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('checkboxes')
                ->render(function (VendorPackage $package){
                    return CheckBox::make('vendorPackages[]')
                        ->value($package->id)
                        ->checked(false);
                }),

            TD::make('package_name', 'Package Name')
                ->render(function (VendorPackage $package) {
                    return Link::make($package->package_name)
                        ->route('platform.package.edit', $package);
                }),
            TD::make('description', 'Package Description')
                ->width('300')
                ->render(function (VendorPackage $package) {
                    return "<a href=packages/{$package->id}/edit>{$package->description}</a>";
                }),
            TD::make('price', 'Price - $USD')
                ->render(function (VendorPackage $package) {
                    return Link::make('$' . number_format($package->price))
                        ->route('platform.package.edit', $package);
                }),
            TD::make('url', 'Package Site')
                ->render(function (VendorPackage $package) {
                    return Link::make($package->url)
                        ->href($package->url == null ? '#' : $package->url);
                }),
            
            TD::make()
                ->width('80')
                ->align(TD::ALIGN_RIGHT)
                ->render(function(VendorPackage $package){
                    return Button::make('Edit')->type(Color::PRIMARY())->method('redirect', ['package_id' => $package->id])->icon('pencil');
                }), 
        ];
    }
}

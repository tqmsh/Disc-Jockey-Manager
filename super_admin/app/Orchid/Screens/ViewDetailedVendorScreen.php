<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;

use App\Models\User;
use App\Models\VendorPaidRegions;
use App\Models\Vendors;
use Orchid\Screen\Sight;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use App\Models\Categories;
use App\Models\Region;
use App\Orchid\Layouts\ViewVendorRegionsLayout;
use Orchid\Screen\TD;


class ViewDetailedVendorScreen extends Screen
{
    public $vendor;
    public $regions;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Vendors $vendor): iterable
    {
        $user = User::where('id', $vendor->user_id)->first();
        $regionids = VendorPaidRegions::where('user_id', $user->id)->pluck('region_id');
        $regions = Region::whereIn('id', $regionids)->get();

        return [
            'vendor' => $vendor,
            'regions' => $regions,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'View Vendor: ' . $this->vendor->company_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
            ->icon('arrow-left')
            ->route('platform.vendor.list'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        // $this->vendor = 'vendor';


        $user = User::where('id', $this->vendor->user_id)->first();
        // dd($user->name);

        return [
            Layout::legend('vendor',[
                Sight::make('company_name', 'Company')
                ->render(function (Vendors $vendors_1 = null) {
                    return $vendors_1->company_name ?? " ";
                }),

                Sight::make('name', 'Username')
                    ->render(function (Vendors $vendors_1 = null) {
                        return User::find($vendors_1->user_id)->name ?? " ";
                    }),

                Sight::make('firstname', 'First Name')
                ->render(function (Vendors $vendors_1 = null) {
                    return User::find($vendors_1->user_id)->firstname ?? " ";
                }),

                Sight::make('lastname', 'Last Name')
                ->render(function (Vendors $vendors_1 = null) {
                    return User::find($vendors_1->user_id)->lastname ?? " ";
                }),

                Sight::make('email', 'Email')
                ->render(function (Vendors $vendors_1 = null) {
                    return $vendors_1->email ?? " ";
                }),

                Sight::make('phone', 'Phone Number')
                ->render(function (Vendors $vendors_1 = null) {
                    return $vendors_1->phonenumber ?? " ";
                }),

                Sight::make('phone', 'Phone Number')
                ->render(function (Vendors $vendors_1 = null) {
                    return $vendors_1->phonenumber ?? " ";
                }),

                Sight::make('category', 'Category')
                ->render(function (Vendors $vendors_1 = null) {
                    return Categories::find($vendors_1->category_id)->name ?? " ";
                }),

                Sight::make('website', 'Website')
                ->render(function (Vendors $vendors_1 = null) {
                    return $vendors_1->website ?? " ";
                }),

                Sight::make('country', 'Country')
                ->render(function (Vendors $vendors_1 = null) {
                    return $vendors_1->country ?? " ";
                }),

                Sight::make('state_province', 'State/Province')
                ->render(function (Vendors $vendors_1 = null) {
                    return $vendors_1->state_province ?? " ";
                }),

                Sight::make('city', 'City')
                ->render(function (Vendors $vendors_1 = null) {
                    return $vendors_1->city ?? " ";
                }),
            ])->title('Vendor Details'),

            // ViewVendorRegionsLayout::class,

            Layout::table('regions', [
                TD::make('name', 'Region Name')
                ->render(function (Region $regions) {
                    return $regions->name;
                }),
                ])->title('Covered Regions'),

        ];
    }
}

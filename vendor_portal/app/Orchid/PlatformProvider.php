<?php

declare(strict_types=1);

namespace App\Orchid;
use App\Models\Vendors;

use Orchid\Support\Color;
use Orchid\Platform\Dashboard;
use Orchid\Screen\Actions\Menu;
use Orchid\Platform\ItemPermission;
use Illuminate\Support\Facades\Auth;
use Orchid\Platform\OrchidServiceProvider;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        abort_if(Auth::user()->role != 4, 403, 'You are not authorized to view this page.');

        $vendor = Vendors::where('user_id', Auth::user()->id)->first();

  
        return [
                
            //MONEY MAKER
            Menu::make('Dashboard')
            ->icon('home')
            ->title('CORE')
            ->route('platform.example'),

            // SHOP
            //!Un-comment when the feature is good to go
            // Menu::make('Shop')
            // ->icon('bag')
            // ->route('platform.shop'),


            //MONEYYY MAKER
            Menu::make('Bids')
            ->icon('diamond')
            ->size()
            ->list([
                Menu::make('Bid Opportunities ')
                ->icon('dollar')
                ->route('platform.bidopportunities.list'),

                Menu::make('Bid History')
                ->icon('clock')
                ->route('platform.bidhistory.list'),

                Menu::make('Your Packages ')
                ->icon('present')
                ->route('platform.package.list'),
            ]),

            //!Un-comment when the feature is good to go
            // Menu::make('Campaigns')
            //     ->icon('picture')
            //     ->route('platform.ad.list'),

            Menu::make('Report a Bug')
                ->icon('bug')
                ->route('platform.bug-reports.list'),
    
            Menu::make('Prom Planner Guide')
                ->icon('book-open')
                ->route('platform.guide.list'),

            Menu::make('Prom Planner Sites')
                ->icon('arrow-down')
                ->list([
                    Menu::make('National Proms')
                        ->icon('ps.national-proms')
                        ->url('https://nationalproms.com'),

                    Menu::make('Prom Planner')
                        ->icon('ps.prom-planner')
                        ->url('https://promplanner.app/'),

                    Menu::make('Prom Marketing')
                        ->icon('ps.prom-marketing')
                        ->url('https://prommarketing.com/'),

                    Menu::make('Prom Committee Expo')
                        ->icon('ps.prom-committee-expo')
                        ->url('https://promcommitteeexpo.com'),

                    Menu::make('Prom Show')
                        ->icon('ps.prom-show')
                        ->url('https://promshow.com'),

                    Menu::make('Prom Vendors')
                        ->icon('ps.prom-vendor')
                        ->url('https://promvendors.com/'),

                    Menu::make('Prom Teen')
                        ->icon('ps.prom-teen')
                        ->url('https://promteen.com/'),
                ]),
                
            // Menu::make('Examples Layouts')
            //     ->title('PLACEHOLDERS')
            //     ->icon('arrow-down')
            //     ->size()
            //     ->list([
                    
            //             Menu::make('Basic Elements')
            //                 ->title('Form controls')
            //                 ->icon('note')
            //                 ->route('platform.example.fields'),

            //             Menu::make('Advanced Elements')
            //                 ->icon('briefcase')
            //                 ->route('platform.example.advanced'),

            //             Menu::make('Text Editors')
            //                 ->icon('list')
            //                 ->route('platform.example.editors'),

            //             Menu::make('Overview layouts')
            //                 ->title('Layouts')
            //                 ->icon('layers')
            //                 ->route('platform.example.layouts'),

            //             Menu::make('Chart tools')
            //                 ->icon('bar-chart')
            //                 ->route('platform.example.charts'),

            //             Menu::make('Cards')
            //                 ->icon('grid')
            //                 ->route('platform.example.cards')
            //                 ->divider(),

            //             Menu::make('Documentation')
            //                 ->title('Docs')
            //                 ->icon('docs')
            //                 ->url('https://orchid.software/en/docs'),

            //             Menu::make('Changelog')
            //                 ->icon('shuffle')
            //                 ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
            //                 ->target('_blank')
            //                 ->badge(function () {
            //                     return Dashboard::version();
            //                 }, Color::DARK()),

            //             Menu::make(__('Roles'))
            //                 ->icon('lock')
            //                 ->route('platform.systems.roles')
            //                 ->permission('platform.systems.roles'),
            //     ]),
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make('Profile')
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}

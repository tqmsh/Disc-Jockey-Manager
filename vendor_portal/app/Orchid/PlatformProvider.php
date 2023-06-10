<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Models\Student;
use App\Models\Vendors;
use Orchid\Support\Color;
use App\Models\Categories;
use App\Models\Localadmin;
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
        return [
                
            //MONEY MAKER
            Menu::make('Dashboard')
            ->icon('home')
            ->title('CORE')
            ->route('platform.example'),

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

            Menu::make('Campaigns')
            ->icon('picture')
            ->route('platform.ad.list'),
    
            Menu::make('Prom Planner Guide')
                ->icon('book-open')
                ->route('platform.course.list'),
                
            Menu::make('Examples Layouts')
                ->title('PLACEHOLDERS')
                ->icon('arrow-down')
                ->size()
                ->list([
                    
                        Menu::make('Basic Elements')
                            ->title('Form controls')
                            ->icon('note')
                            ->route('platform.example.fields'),

                        Menu::make('Advanced Elements')
                            ->icon('briefcase')
                            ->route('platform.example.advanced'),

                        Menu::make('Text Editors')
                            ->icon('list')
                            ->route('platform.example.editors'),

                        Menu::make('Overview layouts')
                            ->title('Layouts')
                            ->icon('layers')
                            ->route('platform.example.layouts'),

                        Menu::make('Chart tools')
                            ->icon('bar-chart')
                            ->route('platform.example.charts'),

                        Menu::make('Cards')
                            ->icon('grid')
                            ->route('platform.example.cards')
                            ->divider(),

                        Menu::make('Documentation')
                            ->title('Docs')
                            ->icon('docs')
                            ->url('https://orchid.software/en/docs'),

                        Menu::make('Changelog')
                            ->icon('shuffle')
                            ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
                            ->target('_blank')
                            ->badge(function () {
                                return Dashboard::version();
                            }, Color::DARK()),

                        Menu::make(__('Roles'))
                            ->icon('lock')
                            ->route('platform.systems.roles')
                            ->permission('platform.systems.roles'),
                ]),
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

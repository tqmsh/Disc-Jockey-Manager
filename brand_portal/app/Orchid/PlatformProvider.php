<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Support\Color;
use Orchid\Platform\Dashboard;
use Orchid\Screen\Actions\Menu;
use Orchid\Platform\ItemPermission;
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
                ->route('platform.dashboard'),

            Menu::make('Roadmap')
                ->icon('map')
                ->route('platform.roadmap'),

            Menu::make('Opportunities')
                ->icon('graph')
                ->route('platform.opportunities'),

            Menu::make('Dresses')
                ->icon('fa.person-dress')
                ->route('platform.dresses'),

            Menu::make('Prom Planner Guide')
                ->icon('book-open')
                ->route('platform.course.list'),

            // Menu::make('Examples Layouts')
            //     ->title('PLACEHOLDERS')
            //     ->icon('arrow-down')
            //     ->size()
            //     ->list([

            //         Menu::make('Basic Elements')
            //             ->title('Form controls')
            //             ->icon('note')
            //             ->route('platform.dashboard.fields'),

            //         Menu::make('Advanced Elements')
            //             ->icon('briefcase')
            //             ->route('platform.dashboard.advanced'),

            //         Menu::make('Text Editors')
            //             ->icon('list')
            //             ->route('platform.dashboard.editors'),

            //         Menu::make('Overview layouts')
            //             ->title('Layouts')
            //             ->icon('layers')
            //             ->route('platform.dashboard.layouts'),

            //         Menu::make('Chart tools')
            //             ->icon('bar-chart')
            //             ->route('platform.dashboard.charts'),

            //         Menu::make('Cards')
            //             ->icon('grid')
            //             ->route('platform.dashboard.cards')
            //             ->divider(),

            //         Menu::make('Documentation')
            //             ->title('Docs')
            //             ->icon('docs')
            //             ->url('https://orchid.software/en/docs'),

            //         Menu::make('Changelog')
            //             ->icon('shuffle')
            //             ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
            //             ->target('_blank')
            //             ->badge(function () {
            //                 return Dashboard::version();
            //             }, Color::DARK()),

            //         Menu::make(__('Roles'))
            //             ->icon('lock')
            //             ->route('platform.systems.roles')
            //             ->permission('platform.systems.roles'),
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

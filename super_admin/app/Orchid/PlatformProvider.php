<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Models\Student;
use Orchid\Support\Color;
use App\Models\Localadmin;
use App\Models\Vendors;
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
            ->route('platform.example'),
            
            
            Menu::make('Vendors')
            ->icon('dollar')
            ->size()
            ->list([
                //vendors nav option
                Menu::make('List')
                ->icon('list')
                ->route('platform.vendor.list'),
                
                //pending vendor nav option
                Menu::make('Pending Vendors')
                ->icon('user-follow')
                ->badge(function () {
                    return count(Vendors::where('account_status', 0)->get());
                })
                ->route('platform.pendingvendor.list'),

                //vendors nav option
                Menu::make('Add Category')
                ->icon('plus')
                ->route('platform.category.create'),
            ]),
            
            //school nav option
            Menu::make('Schools')
                ->icon('building')
                ->route('platform.school.list'),

            Menu::make('Local Admins')
                ->icon('people')
                ->size()
                ->list([
                    //localadmin nav option
                    Menu::make('List')
                        ->icon('list')
                        ->route('platform.localadmin.list'),

                    //pending localadmin nav option
                    Menu::make('Pending Local Admins')
                        ->icon('user-follow')
                        ->badge(function () {
                                    return count(Localadmin::where('account_status', 0)->get());
                                })
                        ->route('platform.pendinglocaladmin.list'),
                ]),

            Menu::make('Students')
                ->icon('graduation')
                ->size()
                ->list([
                    //student nav option
                    Menu::make('List')
                        ->icon('list')
                        ->route('platform.student.list'),
                        
                    //pending student nav option
                    Menu::make('Pending Students')
                        ->icon('user-follow')
                        ->badge(function () {
                                    return count(Student::where('account_status', 0)->get());
                                })
                        ->route('platform.pendingstudent.list'),
                ]),

            //event nav option
            Menu::make('Events')
                ->icon('diamond')
                ->route('platform.event.list'),
                
            Menu::make('Examples Layouts')
                ->title('PLACEHOLDERS')
                ->icon('arrow-down')
                ->size()
                ->list([
                        Menu::make('Email sender')
                            ->icon('envelope-letter')
                            ->route('platform.email')
                            ->title('Tools'),

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

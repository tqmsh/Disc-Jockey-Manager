<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Models\Student;
use Orchid\Support\Color;
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
            ->icon('monitor')
            ->title('CORE')
            ->route('platform.example'),

            //student nav option
            Menu::make('Events')
            ->icon('diamond')
            ->route('platform.event.list'),

            //student nav option
            Menu::make('Student List')
                ->icon('graduation')
                ->route('platform.student.list'),


            //pending student nav option
            Menu::make('Pending Students')
                ->icon('user-follow')
                ->badge(function () {
                            return count(Student::where('school_id', Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id'))->where('account_status', 0)->get());
                        })
                ->route('platform.pendingstudent.list'),
            
                
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

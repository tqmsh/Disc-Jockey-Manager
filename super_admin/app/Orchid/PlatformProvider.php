<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Models\Student;
use App\Models\Vendors;
use App\Models\Campaign;
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
        abort_if(Auth::user()->role != 1, 403, 'You are not authorized to view this page.');

        //FARHAN & ANDY WAS HERE😉
        return [

            //MONEY MAKER
            Menu::make('Dashboard')
            ->icon('home')
            ->title('CORE')
            ->route('platform.example'),

            //MONEYYY MAKER
            Menu::make('Vendors')
            ->icon('dollar')
            ->size()
            ->list([
                //vendors nav option
                Menu::make('List')
                ->icon('list')
                ->route('platform.vendor.list'),

                //vendors bid option
                Menu::make('Bids')
                ->icon('money')
                ->route('platform.bid.list'),

                //pending vendor nav option
                Menu::make('Pending Vendors')
                ->icon('user-follow')
                ->badge(function () {
                    return count(Vendors::where('account_status', 0)->get());
                })
                ->route('platform.pendingvendor.list'),

                //category nav option
                Menu::make('Categories')
                ->icon('layers')
                ->badge(function () {
                    return count(Categories::where('status', 0)->get());
                })
                ->route('platform.category.list'),

                // Campaign list option
                Menu::make('Ad Campaigns')
                ->icon("picture")
                ->badge(function () {
                    return count(Campaign::where('active', 0)->get());
                })
                ->route('platform.ad.list'),

                Menu::make('Vendor Finances')
                ->icon('paypal')
                ->route('platform.vendor.credits'),
            ]),

            Menu::make('Schools')
                ->icon('building')
                ->size()
                ->list([

                    //school nav option
                    Menu::make('List')
                        ->icon('list')
                        ->route('platform.school.list'),

                    //school nav option
                    Menu::make('Regions')
                        ->icon('globe')
                        ->route('platform.region.list'),
                ]),

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

            Menu::make('Songs')
                ->icon('music-tone-alt')
                ->route('platform.songs.list'),

            Menu::make("Groups")
                ->icon('organization')
                ->list([
                    Menu::make('Limo Groups')
                    ->icon('fa.car')
                    ->route('platform.limo-groups'),
                    
                    Menu::make('Beauty Groups')
                    ->icon('fa.shirt')
                    ->route('platform.beauty-groups'),
                ]),

            Menu::make('Contracts')
                ->icon('doc')
                ->route('platform.contract.list'),
            
            Menu::make('Report a Bug')
                ->icon('bug')
                ->route('platform.bug-reports.list'),

            Menu::make('Prom Planner Guide')
                ->icon('book-open')
                ->route('platform.course.list'),

            Menu::make(__('Roles'))
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),

            Menu::make('Prom Planner Sites')
                ->icon('arrow-down')
                ->list([
                    Menu::make('Prom Planner')
                        ->icon('ps.prom-planner')
                        ->url('https://promplanner.app/'),
                    Menu::make('Prom Marketing')
                        ->icon('ps.prom-marketing')
                        ->url('https://prommarketing.com/'),
                    Menu::make('Prom Radio')
                        ->icon('ps.prom-radio')
                        ->url('https://promradio.com/'),
                    Menu::make('Prom Teen')
                        ->icon('ps.prom-teen')
                        ->url('https://promteen.com/'),
                    Menu::make('Prom Vendors')
                        ->icon('ps.prom-vendor')
                        ->url('https://promvendors.com/'),
                ]),

            // Menu::make('Examples Layouts')
            //     ->title('PLACEHOLDERS')
            //     ->icon('arrow-down')
            //     ->size()
            //     ->list([
            //             Menu::make('Email sender')
            //                 ->icon('envelope-letter')
            //                 ->route('platform.email')
            //                 ->title('Tools'),

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

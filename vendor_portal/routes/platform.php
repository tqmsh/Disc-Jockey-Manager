<?php

declare(strict_types=1);

use Tabuna\Breadcrumbs\Trail;
use App\Orchid\Screens\EditAdScreen;
use App\Orchid\Screens\ViewAdScreen;
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\CreateAdScreen;
use App\Orchid\Screens\CreateBidScreen;
use App\Orchid\Screens\ViewEventScreen;
use App\Orchid\Screens\EditPackageScreen;
use App\Orchid\Screens\ViewPackageScreen;
use App\Orchid\Screens\EditEventBidScreen;
use App\Orchid\Screens\CreatePackageScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\EditStudentBidScreen;
use App\Orchid\Screens\ViewBidHistoryScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/


// Orchid main menu
Route::screen('main', ExampleScreen::class)->name('platform.main');

//show events screen
Route::screen('/bidopportunities', ViewEventScreen::class)->name('platform.event.list');

Route::screen('/packages', ViewPackageScreen::class)->name('platform.package.list');

Route::screen('/packages/create', CreatePackageScreen::class)->name('platform.package.create');

Route::screen('/packages/{package}/edit', EditPackageScreen::class)->name('platform.package.edit');

Route::screen('/campaigns', ViewAdScreen::class)->name('platform.ad.list');

Route::screen('/campaigns/create', CreateAdScreen::class)->name('platform.ad.create');

Route::screen('/campaigns/{ad}/edit', EditAdScreen::class)->name('platform.ad.edit');

Route::screen('/bid/{event}/create', CreateBidScreen::class)->name('platform.bid.create');

Route::screen('/bids/history', ViewBidHistoryScreen::class)->name('platform.bidhistory.list');

Route::screen('/eventbid/{eventBid}/edit', EditEventBidScreen::class)->name('platform.eventBid.edit');

Route::screen('/studentbid/{studentBid}/edit', EditStudentBidScreen::class)->name('platform.studentBid.edit');


// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile'));
    });

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(function (Trail $trail, $role) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Role'), route('platform.systems.roles.edit', $role));
    });

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Create'), route('platform.systems.roles.create'));
    });

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Roles'), route('platform.systems.roles'));
    });

// Example...
Route::screen('dashboard', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->push('Dashboard');
    });

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//Route::screen('idea', Idea::class, 'platform.screens.idea');

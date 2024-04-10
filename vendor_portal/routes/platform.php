<?php

declare(strict_types=1);

use App\Orchid\Screens\BuyCreditsScreen;
use App\Models\Vendors;


use Tabuna\Breadcrumbs\Trail;
use App\Orchid\Screens\EditAdScreen;
use App\Orchid\Screens\ViewAdScreen;
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\CreateAdScreen;
use App\Orchid\Screens\ViewGuideScreen;
use App\Orchid\Screens\EditLimoBidScreen;
use App\Orchid\Screens\EditPackageScreen;
use App\Orchid\Screens\ViewPackageScreen;
use App\Orchid\Screens\EditEventBidScreen;
use App\Orchid\Screens\CreatePackageScreen;
use App\Orchid\Screens\EditBeautyBidScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\CreateEventBidScreen;
use App\Orchid\Screens\EditStudentBidScreen;
use App\Orchid\Screens\ViewBidHistoryScreen;
use App\Orchid\Screens\CreateStudentBidScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\ViewSingleLessonScreen;
use App\Orchid\Screens\ViewGuideSectionScreen;
use App\Orchid\Screens\ViewSectionLessonScreen;
use App\Orchid\Screens\CreateLimoGroupBidScreen;
use App\Orchid\Screens\CreateBeautyGroupBidScreen;
use App\Orchid\Screens\ViewBidOpportunitiesScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\CreateBugReportScreen;
use App\Orchid\Screens\ViewBugReportDetailedScreen;
use App\Orchid\Screens\ViewBugReportScreen;

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
Route::screen('main', ExampleScreen::class)->name('platform.main')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->push('Main Menu');
    });

//show events screen
Route::screen('/bidopportunities', ViewBidOpportunitiesScreen::class)->name('platform.bidopportunities.list');

Route::screen('/packages', ViewPackageScreen::class)->name('platform.package.list');

Route::screen('/packages/create', CreatePackageScreen::class)->name('platform.package.create');

Route::screen('/packages/{package}/edit', EditPackageScreen::class)->name('platform.package.edit');

Route::screen('/campaigns', ViewAdScreen::class)->name('platform.ad.list');

Route::screen('/campaigns/create', CreateAdScreen::class)->name('platform.ad.create');

Route::screen('/campaigns/{ad}/edit', EditAdScreen::class)->name('platform.ad.edit');

Route::screen('/event-bid/{event}/create', CreateEventBidScreen::class)->name('platform.bid.create');

Route::screen('/student-bid/{student}/create', CreateStudentBidScreen::class)->name('platform.studentBid.create');

Route::screen('/limo-group-bid/{limoGroup}/create', CreateLimoGroupBidScreen::class)->name('platform.limoGroupBid.create');

Route::screen('/limo-group-bid/{limoGroup}/edit', EditLimoBidScreen::class)->name('platform.limoGroupBid.edit');

Route::screen('/beauty-group-bid/{beautyGroup}/create', CreateBeautyGroupBidScreen::class)->name('platform.beautyGroupBid.create');

Route::screen('/beauty-group-bid/{beautyGroup}/edit', EditBeautyBidScreen::class)->name('platform.beautyGroupBid.edit');

Route::screen('/bids/history', ViewBidHistoryScreen::class)->name('platform.bidhistory.list');

Route::screen('/event-bid/{eventBid}/edit', EditEventBidScreen::class)->name('platform.eventBid.edit');

Route::screen('/studen-tbid/{studentBid}/edit', EditStudentBidScreen::class)->name('platform.studentBid.edit');

Route::screen('/guides', ViewGuideScreen::class)->name('platform.guide.list');

Route::screen('/guides/{guide}/sections', ViewGuideSectionScreen::class)->name('platform.guideSection.list');

Route::screen('/guides/{guide}/sections/{section}/lessons', ViewSectionLessonScreen::class)->name('platform.sectionLesson.list');

Route::screen('/guides/{guide}/sections/{section}/lessons/{lesson}/view', ViewSingleLessonScreen::class)->name('platform.singleLesson.list');

// Bug Reports
Route::screen('/bug-reports/create', CreateBugReportScreen::class)->name('platform.bug-reports.create');
Route::screen('/bug-reports', ViewBugReportScreen::class)->name('platform.bug-reports.list');
Route::screen('/bug-reports/{bug_report}', ViewBugReportDetailedScreen::class)->name('platform.bug-reports.view');

// $vendor = Vendors::where('user_id', Auth::user()->id)->first();

Route::screen('/shop', BuyCreditsScreen::class)
    ->name('platform.shop')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Shop'), route('platform.shop'));
    });

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
            ->push('Main Menu');
    });

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//Route::screen('idea', Idea::class, 'platform.screens.idea');

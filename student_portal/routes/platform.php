<?php

declare(strict_types=1);

use App\Orchid\Screens\PromDateScreen;
use App\Orchid\Screens\ViewCoupleDetailsScreen;
use Tabuna\Breadcrumbs\Trail;
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\ViewEventScreen;
use App\Orchid\Screens\ViewSpecsScreen;
use App\Orchid\Screens\EditSpecsScreen;
use App\Orchid\Screens\ViewGuideScreen;
use App\Orchid\Screens\ViewVotingScreen;
use App\Orchid\Screens\ViewWinnersScreen;
use App\Orchid\Screens\CreateSpecsScreen;
use App\Orchid\Screens\ClaimedDressScreen;
use App\Orchid\Screens\ViewElectionScreen;
use App\Orchid\Screens\EditLimoGroupScreen;
use App\Orchid\Screens\ViewDressListScreen;
use App\Orchid\Screens\ViewEventFoodScreen;
use App\Orchid\Screens\ViewLimoGroupScreen;
use App\Orchid\Screens\ViewEventTableScreen;
use App\Orchid\Screens\ViewStudentBidScreen;
use App\Orchid\Screens\CreateLimoGroupScreen;
use App\Orchid\Screens\EditBeautyGroupScreen;
use App\Orchid\Screens\ViewBeautyGroupScreen;
use App\Orchid\Screens\ViewSingleDressScreen;
use App\Orchid\Screens\ViewSongRequestScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\ViewSingleLessonScreen;
use App\Orchid\Screens\ContactLocalAdminScreen;
use App\Orchid\Screens\CreateBeautyGroupScreen;
use App\Orchid\Screens\CreateBugReportScreen;
use App\Orchid\Screens\CreateSongRequestScreen;
use App\Orchid\Screens\ViewGuideSectionScreen;
use App\Orchid\Screens\ViewDressWishlistScreen;
use App\Orchid\Screens\ViewSectionLessonScreen;
use App\Orchid\Screens\ViewEventInformationScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\ViewBeautyGroupDetailedBidScreen;
use App\Orchid\Screens\ViewSingleFoodScreen;
use App\Orchid\Screens\ViewBugReportDetailedScreen;
use App\Orchid\Screens\ViewBugReportScreen;
use App\Orchid\Screens\ViewChecklistItemScreen;
use App\Orchid\Screens\ViewChecklistScreen;
use App\Orchid\Screens\ViewLimoGroupDetailedBidScreen;
use App\Orchid\Screens\ViewStudentBidDetailedBidScreen;

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
Route::middleware(['cors'])->group(function () {
    Route::screen('/events', ViewEventScreen::class)->name('platform.event.list');
});


//show event registration screen
Route::screen('/events/{event}/register', ViewEventScreen::class)->name('platform.event.register');

Route::screen('/events/{event}/information', ViewEventInformationScreen::class)->name('platform.event.information');

Route::screen('/events/{event}/tables', ViewEventTableScreen::class)->name('platform.event.tables');

Route::screen('/events/{event}/songs', ViewSongRequestScreen::class)->name('platform.songs.list');

Route::screen('/events/{event}/request-song', CreateSongRequestScreen::class)->name('platform.songs.request');

Route::screen('/events/{event_id}/menu', ViewEventFoodScreen::class)->name('platform.eventFood.list');

Route::screen('/events/{event_id}/menu/{food_id}/item', ViewSingleFoodScreen::class)->name('platform.eventFoodSingle.list');

Route::screen('/events/{event}/election', ViewElectionScreen::class)->name('platform.election.list');

Route::screen('/events/election/vote/{position}', ViewVotingScreen::class)->name('platform.election.vote');

Route::screen('/events/election/winners/{election}', ViewWinnersScreen::class)->name('platform.election.winners');

Route::screen('/guides', ViewGuideScreen::class)->name('platform.guide.list');

Route::screen('/guides/{guide}/sections', ViewGuideSectionScreen::class)->name('platform.guideSection.list');

Route::screen('/guides/{guide}/sections/{section}/lessons', ViewSectionLessonScreen::class)->name('platform.sectionLesson.list');

Route::screen('/guides/{guide}/sections/{section}/lessons/{lesson}/view', ViewSingleLessonScreen::class)->name('platform.singleLesson.list');

// View PromDate
Route::screen('/promdate', PromDateScreen::class)->name('platform.promdate');
// View Couple Details
Route::screen('/couple/{couple}', ViewCoupleDetailsScreen::class)->name("platform.couple");

//view student bid screen
Route::screen('/bids', ViewStudentBidScreen::class)->name('platform.studentBids.list');

// detailed bid screens
Route::screen('/bids/beauty-group/{beautyGroupBid}', ViewBeautyGroupDetailedBidScreen::class)->name('platform.studentBids.beautyGroup.view');
Route::screen('/bids/limo-group/{limoGroupBid}', ViewLimoGroupDetailedBidScreen::class)->name('platform.studentBids.limoGroup.view');
Route::screen('/bids/student-bids/{studentBid}', ViewStudentBidDetailedBidScreen::class)->name('platform.studentBids.studentBid.view');

//student specs
Route::screen('/my-specs', ViewSpecsScreen::class)->name('platform.studentSpecs.list');

// Create Specs
Route::screen('/my-specs/create', CreateSpecsScreen::class)->name('platform.specs.create');

Route::screen('/my-specs/{specs:user_id}/edit', EditSpecsScreen::class)->name('platform.specs.edit');

//view limo groups
Route::screen('/limo-groups', ViewLimoGroupScreen::class)->name('platform.limo-groups');

//create limo groups
Route::screen('/limo-groups/create', CreateLimoGroupScreen::class)->name('platform.limo-groups.create');

//edit limo group
Route::screen('/limo-groups/{limoGroup}/edit', EditLimoGroupScreen::class)->name('platform.limo-groups.edit');

//view beauty groups
Route::screen('/beauty-groups', ViewBeautyGroupScreen::class)->name('platform.beauty-groups');

//create beauty groups
Route::screen('/beauty-groups/create', CreateBeautyGroupScreen::class)->name('platform.beauty-groups.create');

//edit beauty group
Route::screen('/beauty-groups/{beautyGroup}/edit', EditBeautyGroupScreen::class)->name('platform.beauty-groups.edit');

Route::screen('/contact-prom-committees', ContactLocalAdminScreen::class)->name('platform.contact-prom-committees');

// Checklists
Route::screen('/checklists', ViewChecklistScreen::class)->name('platform.checklist.list');

// Checklist Items
Route::screen('/checklists/{checklist}/items', ViewChecklistItemScreen::class)->name('platform.checklist-items.list');

// Bug Reports
Route::screen('/bug-reports/create', CreateBugReportScreen::class)->name('platform.bug-reports.create');
Route::screen('/bug-reports', ViewBugReportScreen::class)->name('platform.bug-reports.list');
Route::screen('/bug-reports/{bug_report}', ViewBugReportDetailedScreen::class)->name('platform.bug-reports.view');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile'));
    });

// Example...
Route::screen('dashboard', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->push('Main Menu');
    });

Route::screen('dresses', ViewDressListScreen::class)
    ->name('platform.dresses')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->push('Dresses');
    });

Route::screen('dresses/wishlist', ViewDressWishlistScreen::class)
    ->name('platform.dresses.wishlist')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->push('Dresses');
    });

Route::screen('dresses/id/{dress}', ViewSingleDressScreen::class)
    ->name('platform.dresses.detail')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->push('Dresses');
    });

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//Route::screen('idea', Idea::class, 'platform.screens.idea');

<?php

declare(strict_types=1);

use App\Orchid\Screens\CreateBannedSongsScreen;
use App\Orchid\Screens\CreateTourElementScreen;
use App\Orchid\Screens\EditSongScreen;
use App\Orchid\Screens\EditTourElementScreen;
use App\Orchid\Screens\ViewAllTourElementScreen;
use App\Orchid\Screens\ViewBannedSongsScreen;
use App\Orchid\Screens\ViewCoupleDetailsScreen;
use App\Orchid\Screens\ViewCouplesScreen;
use Tabuna\Breadcrumbs\Trail;
use App\Orchid\Screens\EditAdScreen;
use App\Orchid\Screens\ViewAdScreen;
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\EditEventScreen;
use App\Orchid\Screens\ViewEventScreen;
use App\Orchid\Screens\ViewSongsScreen;
use App\Orchid\Screens\EditGuideScreen;
use App\Orchid\Screens\EditRegionScreen;
use App\Orchid\Screens\EditSchoolScreen;
use App\Orchid\Screens\EditVendorScreen;
use App\Orchid\Screens\ViewAllBidScreen;
use App\Orchid\Screens\ViewGuideScreen;
use App\Orchid\Screens\ViewRegionScreen;
use App\Orchid\Screens\ViewSchoolScreen;
use App\Orchid\Screens\ViewVendorScreen;
use App\Orchid\Screens\ViewDetailedVendorScreen;
use App\Orchid\Screens\CreateEventScreen;
use App\Orchid\Screens\EditStaffScreen;
use App\Orchid\Screens\EmailSenderScreen;
use App\Orchid\Screens\ViewStaffScreen;
use App\Orchid\Screens\CreateSchoolScreen;
use App\Orchid\Screens\CreateVendorScreen;
use App\Orchid\Screens\EditCategoryScreen;
use App\Orchid\Screens\EditEventBidScreen;
use App\Orchid\Screens\ViewCategoryScreen;
use App\Orchid\Screens\ViewEventBidScreen;
use App\Orchid\Screens\CreateStaffScreen;

use App\Orchid\Screens\ViewElectionScreen;
use App\Orchid\Screens\CreateElectionScreen;
use App\Orchid\Screens\EditElectionScreen;
use App\Orchid\Screens\CreatePositionScreen;
use App\Orchid\Screens\EditPositionScreen;
use App\Orchid\Screens\ViewCandidateScreen;
use App\Orchid\Screens\CreateCandidateScreen;
use App\Orchid\Screens\EditCandidateScreen;
use App\Orchid\Screens\ViewWinnersScreen;

use App\Orchid\Screens\ViewVendorCreditsScreen;
use App\Orchid\Screens\ViewVendorPaymentsScreen;


use App\Orchid\Screens\EditLimoGroupScreen;
use App\Orchid\Screens\OrderCategoryScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\ViewLimoGroupScreen;
use App\Orchid\Screens\EditLocaladminScreen;
use App\Orchid\Screens\ViewLocaladminScreen;
use App\Orchid\Screens\ViewRequestersScreen;
use App\Orchid\Screens\CreateLimoGroupScreen;
use App\Orchid\Screens\EditBeautyGroupScreen;
use App\Orchid\Screens\ViewBeautyGroupScreen;
use App\Orchid\Screens\CreateLocaladminScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\ViewEventStudentScreen;
use App\Orchid\Screens\ViewSingleLessonScreen;
use App\Orchid\Screens\ViewSongRequestsScreen;
use App\Orchid\Screens\CreateBeautyGroupScreen;
use App\Orchid\Screens\EditSectionLessonScreen;
use App\Orchid\Screens\ViewGuideSectionScreen;
use App\Orchid\Screens\ViewPendingVendorScreen;
use App\Orchid\Screens\ViewSectionLessonScreen;
use App\Orchid\Screens\ViewPendingStudentScreen;
use App\Orchid\Screens\CreateSectionLessonScreen;
use App\Orchid\Screens\ViewLimoGroupMembersScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\ViewPendingLocaladminScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\ViewBeautyGroupMembersScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\ViewContractScreen;
use App\Orchid\Screens\CreateContractScreen;
use App\Orchid\Screens\CreateNoticeScreen;
use App\Orchid\Screens\CreateUniversalExpenseRevenueScreen;
use App\Orchid\Screens\EditBugReportScreen;
use App\Orchid\Screens\EditChecklistItemScreen;
use App\Orchid\Screens\EditChecklistScreen;
use App\Orchid\Screens\EditContractScreen;
use App\Orchid\Screens\EditNoticeScreen;
use App\Orchid\Screens\ViewBugReportDetailedScreen;
use App\Orchid\Screens\ViewBugReportScreen;
use App\Orchid\Screens\EditStudentBidScreen;
use App\Orchid\Screens\EditUniversalExpenseRevenueScreen;
use App\Orchid\Screens\ViewChecklistItemScreen;
use App\Orchid\Screens\ViewChecklistScreen;
use App\Orchid\Screens\ViewChecklistUsersScreen;
use App\Orchid\Screens\ViewNoticeScreen;
use App\Orchid\Screens\ViewUniversalExpenseRevenueScreen;
use App\Orchid\Screens\ViewUserChecklistItemsScreen;
use App\Orchid\Screens\CreateChecklistScreen;
use App\Orchid\Screens\CreateDisplayAdScreen;
use App\Orchid\Screens\EditDisplayAdScreen;
use App\Orchid\Screens\EditGuideSectionScreen;
use App\Orchid\Screens\EditLoginAdScreen;
use App\Orchid\Screens\ViewAllPromfluencerScreen;
use App\Orchid\Screens\ViewLoginAsGeneratedScreen;
use App\Orchid\Screens\ViewLoginAsScreen;
use App\Orchid\Screens\ViewPromfluencerDetailedScreen;
use App\Orchid\Screens\ViewPromfluencerScreen;
use App\Orchid\Screens\ViewVideoTutorialScreen;

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
Route::redirect('/main', '/admin/dashboard')->name('platform.main');

//show email sender
Route::screen('/email', EmailSenderScreen::class)->name('platform.email');

//show students screen
Route::screen('/students', ViewStaffScreen::class)->name('platform.student.list');

//show create student screen
Route::screen('/students/create', CreateStaffScreen::class)->name('platform.student.create');

//show edit students screen
Route::screen('/students/{student}/edit', EditStaffScreen::class)->name('platform.student.edit');

//show vendors screen
Route::screen('/vendors', ViewVendorScreen::class)->name('platform.vendor.list');

// Show paid regions of venders
Route::screen('/vendors/{vendor}/paid', ViewDetailedVendorScreen::class)->name('platform.vendor.detailed');

//show create vendors screen
Route::screen('/vendors/create', CreateVendorScreen::class)->name('platform.vendor.create');

//show edit vendors screen
Route::screen('/vendors/{vendor}/edit', EditVendorScreen::class)->name('platform.vendor.edit');

//Vendor Credits
Route::screen('/vendors/credits', ViewVendorCreditsScreen::class)->name('platform.vendor.credits');

Route::screen('/vendors/{vendor}/payments', ViewVendorPaymentsScreen::class)->name('platform.vendor.payments');



//show schools screen
Route::screen('/schools', ViewSchoolScreen::class)->name('platform.school.list');

//show create school screen
Route::screen('/schools/create', CreateSchoolScreen::class)->name('platform.school.create');

//show edit schools screen
Route::screen('/schools/{school}/edit', EditSchoolScreen::class)->name('platform.school.edit');

//show localadmin screen
Route::screen('/localadmins', ViewLocaladminScreen::class)->name('platform.localadmin.list');

//show edit localadmin screen
Route::screen('/localadmins/{localadmin}/edit', EditLocaladminScreen::class)->name('platform.localadmin.edit');

//show create local admin screen
Route::screen('/localadmins/create', CreateLocaladminScreen::class)->name('platform.localadmin.create');

//show events screen
Route::screen('/events', ViewEventScreen::class)->name('platform.event.list');

//show edit event screen
Route::screen('/events/{event}/edit', EditEventScreen::class)->name('platform.event.edit');

//show create event screen
Route::screen('/events/create', CreateEventScreen::class)->name('platform.event.create');

//show add table screen for a event
Route::screen('/events/{event}/addTable', EditEventScreen::class)->name('platform.table.create');

//show pending localadmin screen
Route::screen('/pendinglocaladmins', ViewPendingLocaladminScreen::class)->name('platform.pendinglocaladmin.list');

//show pending vendors screen
Route::screen('/pendingvendors', ViewPendingVendorScreen::class)->name('platform.pendingvendor.list');

//show pending student screen
Route::screen('/pendingstudents', ViewPendingStudentScreen::class)->name('platform.pendingstudent.list');

//show view category screen
Route::screen('/categories', ViewCategoryScreen::class)->name('platform.category.list');

//Show order category screen
Route::screen("/categories/order", OrderCategoryScreen::class)->name("platform.category.order");

//show edit category screen
Route::screen('/categories/{category}/edit', EditCategoryScreen::class)->name('platform.category.edit');

//show view region screen
Route::screen('/regions', ViewRegionScreen::class)->name('platform.region.list');

Route::screen('/songs', ViewSongsScreen::class)->name('platform.songs.list');
Route::screen('/songs/edit/{song?}', EditSongScreen::class)->name('platform.songs.edit');

//show edit region screen
Route::screen('/regions/{regions}/edit', EditRegionScreen::class)->name('platform.region.edit');

Route::screen('/bids', ViewAllBidScreen::class)->name('platform.bid.list');

//edit bid screen
Route::screen('/bids/events/{bid}/edit', EditEventBidScreen::class)->name('platform.eventBid.edit');
Route::screen('/bids/students/{bid}/edit', EditStudentBidScreen::class)->name('platform.studentBid.edit');

Route::screen('/events/bids/{event_id}', ViewEventBidScreen::class)->name('platform.eventBids.list');

Route::screen('/events/students/{event_id}', ViewEventStudentScreen::class)->name('platform.eventStudents.list');

Route::screen('/events/{event_id}/songRequests', ViewSongRequestsScreen::class)->name('platform.songreq.list');
Route::screen('/events/{event_id}/banned-songs', ViewBannedSongsScreen::class)->name('platform.bannedSongs.list');
Route::screen('/events/{event_id}/banned-songs/add', CreateBannedSongsScreen::class)->name('platform.bannedSongs.create');
Route::screen('/events/{song_id}/{event_id}/requesters', ViewRequestersScreen::class)->name('platform.songRequesters.list');

//view guides screen route
Route::screen('/guides', ViewGuideScreen::class)->name('platform.guide.list');

//edit guide screen route
Route::screen('/guides/{guide}/edit', EditGuideScreen::class)->name('platform.guide.edit');

//view guide section screen route
Route::screen('/guides/{guide}/sections', ViewGuideSectionScreen::class)->name('platform.guideSection.list');

//edit guide section screen route
Route::screen('/guides/{guide}/sections/{section}/edit', EditGuideSectionScreen::class)->name('platform.guideSection.edit');

//view lessons in a guide section screen route
Route::screen('/guides/{guide}/sections/{section}/lessons', ViewSectionLessonScreen::class)->name('platform.sectionLesson.list');

//edit lesson in a guide section screen route
Route::screen('/guides/{guide}/sections/{section}/lessons/{lesson}/edit', EditSectionLessonScreen::class)->name('platform.sectionLesson.edit');

//view a single section lesson screen route
Route::screen('/guides/{guide}/sections/{section}/lessons/{lesson}/view', ViewSingleLessonScreen::class)->name('platform.singleLesson.list');

//create a section lesson screen route
Route::screen('/guides/{guide}/sections/{section}/lessons/create', CreateSectionLessonScreen::class)->name('platform.sectionLesson.create');

// Campaigns
Route::screen('/campaigns', ViewAdScreen::class)->name('platform.ad.list');
Route::screen('/campaigns/{ad}/edit', EditAdScreen::class)->name('platform.ad.edit');

// Login Ads
Route::screen('/campaigns/login-ad/{loginAd}/edit', EditLoginAdScreen::class)->name('platform.ad.login-ad.edit');

// Display Ads (ad blockers block routes with "display ads" in it, so we go with propoganda)
Route::screen('/campaigns/propoganda/create', CreateDisplayAdScreen::class)->name('platform.ad.create.display-ad');
Route::screen('/campaigns/propoganda/{display_ad}/edit', EditDisplayAdScreen::class)->name('platform.ad.edit.display-ad');

Route::screen('/couples', ViewCouplesScreen::class)->name('platform.couples.list');
Route::screen('/couples/{couple}', ViewCoupleDetailsScreen::class)->name('platform.couples.info');

//Election
Route::screen('/events/promvote/{event_id}', ViewElectionScreen::class)->name('platform.eventPromvote.list');

Route::screen('/events/promvote/{event_id}/create', CreateElectionScreen::class)->name('platform.eventPromvote.create');

Route::screen('/events/promvote/{event_id}/edit', EditElectionScreen::class)->name('platform.eventPromvote.edit');

Route::screen('/events/promvote/{event_id}/createPosition', CreatePositionScreen::class)->name('platform.eventPromvotePosition.create');

Route::screen('/events/promvote/{position_id}/editPosition', EditPositionScreen::class)->name('platform.eventPromvotePosition.edit');

Route::screen('/events/promvote/{position_id}/candidate', ViewCandidateScreen::class)->name('platform.eventPromvotePositionCandidate.list');

Route::screen('/events/promvote/{position_id}/candidate/create', CreateCandidateScreen::class)->name('platform.eventPromvotePositionCandidate.create');

Route::screen('/events/promvote/candidate/{candidate_id}/edit', EditCandidateScreen::class)->name('platform.eventPromvotePositionCandidate.edit');

Route::screen('/events/election/winners/{election}', ViewWinnersScreen::class)->name('platform.election.winners');

//view limo groups
Route::screen('/limo-groups', ViewLimoGroupScreen::class)->name('platform.limo-groups');
//create limo groups
Route::screen('/limo-groups/create', CreateLimoGroupScreen::class)->name('platform.limo-groups.create');
//edit limo group
Route::screen('/limo-groups/{limoGroup}/edit', EditLimoGroupScreen::class)->name('platform.limo-groups.edit');
//view limo group members
Route::screen('/limo-groups/{limoGroup}/members', ViewLimoGroupMembersScreen::class)->name('platform.limo-groups.members');

//view beauty groups
Route::screen('/beauty-groups', ViewBeautyGroupScreen::class)->name('platform.beauty-groups');
//create beauty groups
Route::screen('/beauty-groups/create', CreateBeautyGroupScreen::class)->name('platform.beauty-groups.create');
//edit beauty group
Route::screen('/beauty-groups/{beautyGroup}/edit', EditBeautyGroupScreen::class)->name('platform.beauty-groups.edit');
//view beauty group members
Route::screen('/beauty-groups/{beautyGroup}/members', ViewBeautyGroupMembersScreen::class)->name('platform.beauty-groups.members');

// Contracts
Route::screen('/contracts', ViewContractScreen::class)->name('platform.contract.list');
Route::screen('/contracts/create', CreateContractScreen::class)->name('platform.contract.create');
Route::screen('/contracts/{contract}/edit', EditContractScreen::class)->name('platform.contract.edit');

// Universal Expenses/Revenues
Route::screen('/expenses-revenues', ViewUniversalExpenseRevenueScreen::class)->name('platform.universal-expense-revenue.list');
Route::screen('/expenses-revenues/create', CreateUniversalExpenseRevenueScreen::class)->name('platform.universal-expense-revenue.create');
Route::screen('/expenses-revenues/{expenseRevenue}/edit', EditUniversalExpenseRevenueScreen::class)->name('platform.universal-expense-revenue.edit');

// Notices
Route::screen('/notices', ViewNoticeScreen::class)->name('platform.notice.list');
Route::screen('/notices/create', CreateNoticeScreen::class)->name('platform.notice.create');
Route::screen('/notices/{notice}/edit', EditNoticeScreen::class)->name('platform.notice.edit');

// Checklists
Route::screen('/checklists', ViewChecklistScreen::class)->name('platform.checklist.list');
Route::screen('/checklists/create', CreateChecklistScreen::class)->name('platform.checklist.create');
Route::screen('/checklists/{checklist}/edit', EditChecklistScreen::class)->name('platform.checklist.edit');
Route::screen('/checklists/{checklist}/users', ViewChecklistUsersScreen::class)->name('platform.checklist.users');
Route::screen('/checklists/{checklist}/users/{user}/items', ViewUserChecklistItemsScreen::class)->name('platform.checklist.users.view');

// Checklist Items
Route::screen('/checklists/{checklist}/items', ViewChecklistItemScreen::class)->name('platform.checklist-items.list');
Route::screen('/checklists/{checklist}/items/{checklist_item}/edit', EditChecklistItemScreen::class)->name('platform.checklist-items.edit');

// Promfluence
Route::screen('/promfluence', ViewPromfluencerScreen::class)->name('platform.promfluencer.list');
Route::screen('/promfluence/{promfluencer_id}', ViewPromfluencerDetailedScreen::class)->name('platform.promfluencer.view');

// Bug Reports
Route::screen('/bug-reports', ViewBugReportScreen::class)->name('platform.bug-reports.list');
Route::screen('/bug-reports/{bug_report}/edit', EditBugReportScreen::class)->name('platform.bug-reports.edit');
Route::screen('/bug-reports/{bug_report}', ViewBugReportDetailedScreen::class)->name('platform.bug-reports.view');

// Video Tutorials
Route::screen('/video-tutorials', ViewVideoTutorialScreen::class)->name('platform.video-tutorials.view');

// Tour Stuff
Route::screen('/tourel/create', CreateTourElementScreen::class)->name('platform.tour-element.create');
Route::screen('/tourel/{tourElement}/edit', EditTourElementScreen::class)->name('platform.tour-element.edit');
Route::screen('/tourel', ViewAllTourElementScreen::class)->name('platform.tour-element.list');

// Login as
Route::screen('/login-as', ViewLoginAsScreen::class)->name('platform.login-as.view');
Route::screen('/login-as/generated/{loginAs}', ViewLoginAsGeneratedScreen::class)->name('platform.login-as.generated');

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
    ->name('platform.example');

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//Route::screen('idea', Idea::class, 'platform.screens.idea');

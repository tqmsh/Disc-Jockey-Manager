<?php

declare(strict_types=1);

use App\Orchid\Screens\EditBudgetScreen;
use Tabuna\Breadcrumbs\Trail;
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\EditEventScreen;
use App\Orchid\Screens\ViewEventScreen;
use App\Orchid\Screens\ViewCourseScreen;
use App\Orchid\Screens\CreateEventScreen;
use App\Orchid\Screens\EditStudentScreen;
use App\Orchid\Screens\EmailSenderScreen;
use App\Orchid\Screens\ViewStudentScreen;
use App\Orchid\Screens\ViewWinnersScreen;
use App\Orchid\Screens\EditElectionScreen;
use App\Orchid\Screens\EditPositionScreen;
use App\Orchid\Screens\ViewElectionScreen;
use App\Orchid\Screens\ViewEventBidScreen;
use App\Orchid\Screens\CreateStudentScreen;
use App\Orchid\Screens\EditCandidateScreen;
use App\Orchid\Screens\SuggestVendorScreen;
use App\Orchid\Screens\ViewCandidateScreen;
use App\Orchid\Screens\ViewEventFoodScreen;
use App\Orchid\Screens\ViewLimoGroupScreen;
use App\Orchid\Screens\AddBannedSongsScreen;
use App\Orchid\Screens\ContactStudentScreen;
use App\Orchid\Screens\CreateElectionScreen;
use App\Orchid\Screens\CreatePositionScreen;
use App\Orchid\Screens\ViewRequestersScreen;
use App\Orchid\Screens\CreateCandidateScreen;
use App\Orchid\Screens\ViewBannedSongsScreen;
use App\Orchid\Screens\ViewBeautyGroupScreen;
use App\Orchid\Screens\ViewNoPlaySongsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\ViewEventStudentScreen;
use App\Orchid\Screens\ViewSingleLessonScreen;
use App\Orchid\Screens\ViewSongRequestsScreen;
use App\Orchid\Screens\CreateBannedSongsScreen;
use App\Orchid\Screens\CreateFoodScreen;
use App\Orchid\Screens\EditFoodScreen;
use App\Orchid\Screens\ViewCourseSectionScreen;
use App\Orchid\Screens\ViewSectionLessonScreen;
use App\Orchid\Screens\ViewPendingStudentScreen;
use App\Orchid\Screens\ViewLimoGroupMembersScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\ViewBeautyGroupMembersScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\ViewPromProfitScreen;
use App\Orchid\Screens\ViewPromBudgetScreen;
use App\Orchid\Screens\ViewPromActualScreen;
use App\Http\Controllers\BudgetPDFController;
use App\Http\Controllers\ActualPDFController;
use App\Orchid\Screens\EditActualScreen;

use App\Orchid\Screens\ViewCoupleDetailsScreen;
use App\Orchid\Screens\ViewCouplesScreen;
use App\Orchid\Screens\ViewContractScreen;
use App\Orchid\Screens\CreateBugReportScreen;


use App\Orchid\Screens\CreatePromHistoryScreen;
use App\Orchid\Screens\EditPromHistoryScreen;
use App\Orchid\Screens\ViewBugReportDetailedScreen;
use App\Orchid\Screens\ViewBugReportScreen;
use App\Orchid\Screens\ViewEventDetailedBidScreen;

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

//show email sender
Route::screen('/email', EmailSenderScreen::class)->name('platform.email');

//show students screen
Route::screen('/students', ViewStudentScreen::class)->name('platform.student.list');

//show create student screen
Route::screen('/students/create', CreateStudentScreen::class)->name('platform.student.create');

//show edit students screen
Route::screen('/students/{student}/edit', EditStudentScreen::class)->name('platform.student.edit');

//show events screen
Route::screen('/events', ViewEventScreen::class)->name('platform.event.list');

//show edit event screen
Route::screen('/events/{event}/edit', EditEventScreen::class)->name('platform.event.edit');

//show create event screen
Route::screen('/events/create', CreateEventScreen::class)->name('platform.event.create');

//show pending student screen
Route::screen('/pendingstudents', ViewPendingStudentScreen::class)->name('platform.pendingstudent.list');

Route::screen('/events/bids/{event_id}', ViewEventBidScreen::class)->name('platform.eventBids.list');

// detailed bid screen
Route::screen('/events/bids/{event}/{eventBid}', ViewEventDetailedBidScreen::class)->name('platform.eventBids.view');

Route::screen('/events/students/{event_id}', ViewEventStudentScreen::class)->name('platform.eventStudents.list');

Route::screen('/events/suggestVendor', SuggestVendorScreen::class)->name('platform.suggestVendor.create');

Route::screen('/events/prom-history/create/{event_id}', CreatePromHistoryScreen::class)->name('platform.eventHistory.create');

Route::screen('/events/prom-history/edit/{event_id}', EditPromHistoryScreen::class)->name('platform.eventHistory.edit');

Route::screen('/courses', ViewCourseScreen::class)->name('platform.course.list');

Route::screen('/courses/{course}/sections', ViewCourseSectionScreen::class)->name('platform.courseSection.list');

Route::screen('/courses/{course}/sections/{section}/lessons', ViewSectionLessonScreen::class)->name('platform.sectionLesson.list');

Route::screen('/courses/{course}/sections/{section}/lessons/{lesson}/view', ViewSingleLessonScreen::class)->name('platform.singleLesson.list');

Route::screen('/events/{event_id}/song-requests', ViewSongRequestsScreen::class)->name('platform.songreq.list');
Route::screen('/events/{event_id}/banned-songs', ViewBannedSongsScreen::class)->name('platform.bannedSongs.list');
Route::screen('/events/{event_id}/banned-songs/add', CreateBannedSongsScreen::class)->name('platform.bannedSongs.create');
Route::screen('/events/{event_id}/menu', ViewEventFoodScreen::class)->name('platform.eventFood.list');
Route::screen('/events/{event_id}/menu/add-item', CreateFoodScreen::class)->name('platform.eventFood.create');
Route::screen('/events/{event_id}/menu/{food_id}/edit', EditFoodScreen::class)->name('platform.eventFood.edit');
Route::screen('/events/{song_id}/{event_id}/requesters', ViewRequestersScreen::class)->name('platform.songRequesters.list');

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

//limo group
Route::screen('/limo-groups', ViewLimoGroupScreen::class)->name('platform.limo-groups');
Route::screen('/limo-groups/{limo_group_id}/members', ViewLimoGroupMembersScreen::class)->name('platform.limo-groups.members');

//beauty groups
Route::screen('/beauty-groups', ViewBeautyGroupScreen::class)->name('platform.beauty-groups');
Route::screen('/beauty-groups/{beauty_group_id}/members', ViewBeautyGroupMembersScreen::class)->name('platform.beauty-groups.members');

Route::screen('/contact-students', ContactStudentScreen::class)->name('platform.contact-students');

// Contracts
//Route::screen('/contracts', ViewContractScreen::class)->name('platform.contract.list');

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

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//show profit screen
Route::screen('/profit', ViewPromProfitScreen::class)->name('platform.profit.list');

//show budget screen
Route::screen('/events/{event_id}/budget', ViewPromBudgetScreen::class)->name('platform.budget.list');

//show budget edit screen
Route::screen('/events/{event_id}/budget/edit/{id}', EditBudgetScreen::class)->name('platform.budget.edit');

//show actual screen
Route::screen('/events/{event_id}/actual', ViewPromActualScreen::class)->name('platform.actual.list');

//show actual edit screen
Route::screen('/events/{event_id}/actual/edit/{id}', EditActualScreen::class)->name('platform.actual.edit');

//show budget income statement pdf screen
Route::get('/events/{event_id}/budget/view-pdf', [BudgetPDFController::class, 'viewPDF'])->name('platform.budget.viewPDF');

Route::screen('/events/{event_id}/budget/view-pdf', ViewPromBudgetScreen::class)->name('platform.budget.viewPDF.switch');

//download budget income statement pdf
Route::get('/events/{event_id}/budget/download-pdf', [BudgetPDFController::class, 'downloadPDF'])->name('platform.budget.downloadPDF');

Route::screen('/events/{event_id}/budget/download-pdf', ViewPromBudgetScreen::class)->name('platform.budget.downloadPDF.switch');

//show actual income statement pdf screen
Route::get('/events/{event_id}/actual/view-pdf', [ActualPDFController::class, 'viewPDF'])->name('platform.actual.viewPDF');

Route::screen('/events/{event_id}/actual/view-pdf', ViewPromBudgetScreen::class)->name('platform.actual.viewPDF.switch');

//download actual income statement pdf
Route::get('/events/{event_id}/actual/download-pdf', [ActualPDFController::class, 'downloadPDF'])->name('platform.actual.downloadPDF');

Route::screen('/events/{event_id}/actual/download-pdf', ViewPromBudgetScreen::class)->name('platform.actual.downloadPDF.switch');

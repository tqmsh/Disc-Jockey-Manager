<?php

declare(strict_types=1);

use App\Orchid\Screens\CreateCandidateScreen;
use App\Orchid\Screens\CreateElectionScreen;
use Tabuna\Breadcrumbs\Trail;
use Illuminate\Support\Facades\Route;;
use App\Orchid\Screens\EditEventScreen;
use App\Orchid\Screens\ViewEventScreen;
use App\Orchid\Screens\ViewCourseScreen;
use App\Orchid\Screens\CreateEventScreen;
use App\Orchid\Screens\CreatePositionScreen;
use App\Orchid\Screens\EditStudentScreen;
use App\Orchid\Screens\EmailSenderScreen;
use App\Orchid\Screens\ViewStudentScreen;
use App\Orchid\Screens\ViewElectionScreen;
use App\Orchid\Screens\ViewEventBidScreen;
use App\Orchid\Screens\CreateStudentScreen;
use App\Orchid\Screens\EditCandidateScreen;
use App\Orchid\Screens\EditElectionScreen;
use App\Orchid\Screens\EditPositionScreen;
use App\Orchid\Screens\SuggestVendorScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\ViewEventStudentScreen;
use App\Orchid\Screens\ViewSingleLessonScreen;
use App\Orchid\Screens\ViewCourseSectionScreen;
use App\Orchid\Screens\ViewSectionLessonScreen;
use App\Orchid\Screens\ViewPendingStudentScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\ViewCandidateScreen;
use App\Orchid\Screens\ViewSongRequestsScreen;
use App\Orchid\Screens\ViewNoPlaySongsScreen;
use App\Orchid\Screens\ViewRequestersScreen;
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

Route::screen('/events/students/{event_id}', ViewEventStudentScreen::class)->name('platform.eventStudents.list');

Route::screen('/events/suggestVendor', SuggestVendorScreen::class)->name('platform.suggestVendor.create');

Route::screen('/courses', ViewCourseScreen::class)->name('platform.course.list');

Route::screen('/courses/{course}/sections', ViewCourseSectionScreen::class)->name('platform.courseSection.list');

Route::screen('/courses/{course}/sections/{section}/lessons', ViewSectionLessonScreen::class)->name('platform.sectionLesson.list');

Route::screen('/courses/{course}/sections/{section}/lessons/{lesson}/view', ViewSingleLessonScreen::class)->name('platform.singleLesson.list');

Route::screen('/events/{event_id}/songRequests', ViewSongRequestsScreen::class)->name('platform.songreq.list');

Route::screen('/events/{songRequest_id}/requesters', ViewRequestersScreen::class)->name('platform.requesters.list');

//Election
Route::screen('/events/promvote/{event_id}', ViewElectionScreen::class)->name('platform.eventPromvote.list');

Route::screen('/events/promvote/{event_id}/create', CreateElectionScreen::class)->name('platform.eventPromvote.create');

Route::screen('/events/promvote/{event_id}/edit', EditElectionScreen::class)->name('platform.eventPromvote.edit');

Route::screen('/events/promvote/{event_id}/createPosition', CreatePositionScreen::class)->name('platform.eventPromvotePosition.create');

Route::screen('/events/promvote/{position_id}/editPosition', EditPositionScreen::class)->name('platform.eventPromvotePosition.edit');

Route::screen('/events/promvote/{position_id}/candidate', ViewCandidateScreen::class)->name('platform.eventPromvotePositionCandidate.list');

Route::screen('/events/promvote/{position_id}/candidate/create', CreateCandidateScreen::class)->name('platform.eventPromvotePositionCandidate.create');

Route::screen('/events/promvote/candidate/{candidate_id}/edit', EditCandidateScreen::class)->name('platform.eventPromvotePositionCandidate.edit');


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
            ->push('Example screen');
    });

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

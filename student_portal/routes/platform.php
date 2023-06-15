<?php

declare(strict_types=1);

use Tabuna\Breadcrumbs\Trail;
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\ViewEventScreen;
use App\Orchid\Screens\ViewSpecsScreen;
use App\Orchid\Screens\ViewCourseScreen;
use App\Orchid\Screens\ViewEventTableScreen;
use App\Orchid\Screens\ViewStudentBidScreen;
use App\Orchid\Screens\ViewSongRequestScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\ViewSingleLessonScreen;
use App\Orchid\Screens\ViewCourseSectionScreen;
use App\Orchid\Screens\ViewSectionLessonScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\ViewElectionScreen;
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
Route::screen('/events', ViewEventScreen::class)->name('platform.event.list');

//show event registration screen
Route::screen('/events/{event}/register', ViewEventScreen::class)->name('platform.event.register');

Route::screen('/events/{event}/tables', ViewEventTableScreen::class)->name('platform.event.tables');

Route::screen('/events/{event}/songs', ViewSongRequestScreen::class)->name('platform.songs.list');

Route::screen('/events/{event}/election', ViewElectionScreen::class)->name('platform.election.list');

Route::screen('/courses', ViewCourseScreen::class)->name('platform.course.list');

Route::screen('/courses/{course}/sections', ViewCourseSectionScreen::class)->name('platform.courseSection.list');

Route::screen('/courses/{course}/sections/{section}/lessons', ViewSectionLessonScreen::class)->name('platform.sectionLesson.list');

Route::screen('/courses/{course}/sections/{section}/lessons/{lesson}/view', ViewSingleLessonScreen::class)->name('platform.singleLesson.list');

//view student bid screen
Route::screen('/bids', ViewStudentBidScreen::class)->name('platform.studentBids.list');

//student specs
Route::screen('/my-specs', ViewSpecsScreen::class)->name('platform.studentSpecs.list');

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
            ->push('Dashboard');
    });

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//Route::screen('idea', Idea::class, 'platform.screens.idea');

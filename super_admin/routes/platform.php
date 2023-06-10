<?php

declare(strict_types=1);

use App\Orchid\Screens\EditAdScreen;
use App\Orchid\Screens\ViewAdScreen;
use Tabuna\Breadcrumbs\Trail;
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\EditEventScreen;
use App\Orchid\Screens\ViewEventScreen;
use App\Orchid\Screens\EditCourseScreen;
use App\Orchid\Screens\EditRegionScreen;
use App\Orchid\Screens\EditSchoolScreen;
use App\Orchid\Screens\EditVendorScreen;
use App\Orchid\Screens\ViewAllBidScreen;
use App\Orchid\Screens\ViewCourseScreen;
use App\Orchid\Screens\ViewRegionScreen;
use App\Orchid\Screens\ViewSchoolScreen;
use App\Orchid\Screens\ViewVendorScreen;
use App\Orchid\Screens\CreateEventScreen;
use App\Orchid\Screens\EditStudentScreen;
use App\Orchid\Screens\EmailSenderScreen;
use App\Orchid\Screens\ViewStudentScreen;
use App\Orchid\Screens\CreateSchoolScreen;
use App\Orchid\Screens\CreateVendorScreen;
use App\Orchid\Screens\EditCategoryScreen;
use App\Orchid\Screens\EditEventBidScreen;
use App\Orchid\Screens\ViewCategoryScreen;
use App\Orchid\Screens\ViewSongsScreen;
use App\Orchid\Screens\ViewEventBidScreen;
use App\Orchid\Screens\CreateStudentScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\CreateEventBidScreen;
use App\Orchid\Screens\EditLocaladminScreen;
use App\Orchid\Screens\ViewLocaladminScreen;
use App\Orchid\Screens\CreateLocaladminScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\ViewEventStudentScreen;
use App\Orchid\Screens\ViewSingleLessonScreen;
use App\Orchid\Screens\ViewSongRequestsScreen;
use App\Orchid\Screens\EditCourseSectionScreen;
use App\Orchid\Screens\EditSectionLessonScreen;
use App\Orchid\Screens\ViewCourseSectionScreen;
use App\Orchid\Screens\ViewPendingVendorScreen;
use App\Orchid\Screens\ViewSectionLessonScreen;
use App\Orchid\Screens\ViewPendingStudentScreen;
use App\Orchid\Screens\CreateSectionLessonScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\ViewPendingLocaladminScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
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

//show vendors screen
Route::screen('/vendors', ViewVendorScreen::class)->name('platform.vendor.list');

//show create vendors screen
Route::screen('/vendors/create', CreateVendorScreen::class)->name('platform.vendor.create');

//show edit vendors screen
Route::screen('/vendors/{vendor}/edit', EditVendorScreen::class)->name('platform.vendor.edit');

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

//show edit category screen
Route::screen('/categories/{category}/edit', EditCategoryScreen::class)->name('platform.category.edit');

//show view region screen
Route::screen('/regions', ViewRegionScreen::class)->name('platform.region.list');

Route::screen('/songs', ViewSongsScreen::class)->name('platform.songs.list');

//show edit region screen
Route::screen('/regions/{regions}/edit', EditRegionScreen::class)->name('platform.region.edit');

Route::screen('/bids', ViewAllBidScreen::class)->name('platform.bid.list');

//edit bid screen
Route::screen('/bids/{bid}/edit', EditEventBidScreen::class)->name('platform.bid.edit');

Route::screen('/events/bids/{event_id}', ViewEventBidScreen::class)->name('platform.eventBids.list');

Route::screen('/events/students/{event_id}', ViewEventStudentScreen::class)->name('platform.eventStudents.list');

Route::screen('/events/{event_id}/songRequests', ViewSongRequestsScreen::class)->name('platform.songreq.list');
Route::screen('/events/{songReq_id}/{event_id}/requesters', ViewRequestersScreen::class)->name('platform.requesters.list');

//view courses screen route
Route::screen('/courses', ViewCourseScreen::class)->name('platform.course.list');

//edit course screen route
Route::screen('/courses/{course}/edit', EditCourseScreen::class)->name('platform.course.edit');

//view course section screen route
Route::screen('/courses/{course}/sections', ViewCourseSectionScreen::class)->name('platform.courseSection.list');

//edit course section screen route
Route::screen('/courses/{course}/sections/{section}/edit', EditCourseSectionScreen::class)->name('platform.courseSection.edit');

//view lessons in a course section screen route
Route::screen('/courses/{course}/sections/{section}/lessons', ViewSectionLessonScreen::class)->name('platform.sectionLesson.list');

//edit lesson in a course section screen route
Route::screen('/courses/{course}/sections/{section}/lessons/{lesson}/edit', EditSectionLessonScreen::class)->name('platform.sectionLesson.edit');

//view a single section lesson screen route
Route::screen('/courses/{course}/sections/{section}/lessons/{lesson}/view', ViewSingleLessonScreen::class)->name('platform.singleLesson.list');

//create a section lesson screen route
Route::screen('/courses/{course}/sections/{section}/lessons/create', CreateSectionLessonScreen::class)->name('platform.sectionLesson.create');

Route::screen('/campaigns', ViewAdScreen::class)->name('platform.ad.list');
Route::screen('/campaigns/{ad}/edit', EditAdScreen::class)->name('platform.ad.edit');


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

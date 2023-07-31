<?php

declare(strict_types=1);

use App\Orchid\Screens\BulkUploadDressScreen;
use App\Orchid\Screens\EditDressScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\DashboardScreen;
use App\Orchid\Screens\ViewDressListScreen;
use App\Orchid\Screens\OpportunitiesScreen;
use App\Orchid\Screens\RoadmapScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\ViewCourseScreen;
use App\Orchid\Screens\ViewCourseSectionScreen;
use App\Orchid\Screens\ViewSingleDressScreen;
use App\Orchid\Screens\ViewSectionLessonScreen;
use App\Orchid\Screens\ViewSingleLessonScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

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
Route::screen('main', DashboardScreen::class)->name('platform.main');

Route::screen('/courses', ViewCourseScreen::class)->name('platform.course.list');

Route::screen('/courses/{course}/sections', ViewCourseSectionScreen::class)->name('platform.courseSection.list');

Route::screen('/courses/{course}/sections/{section}/lessons', ViewSectionLessonScreen::class)->name('platform.sectionLesson.list');

Route::screen('/courses/{course}/sections/{section}/lessons/{lesson}/view', ViewSingleLessonScreen::class)->name('platform.singleLesson.list');

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
Route::screen('dashboard', DashboardScreen::class)
    ->name('platform.dashboard')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->push('Dashboard');
    });

Route::screen('roadmap', RoadmapScreen::class)
    ->name('platform.roadmap')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->push('Roadmap');
    });

Route::screen('opportunities', OpportunitiesScreen::class)
    ->name('platform.opportunities')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->push('Opportunities');
    });

Route::screen('dresses', ViewDressListScreen::class)
    ->name('platform.dresses')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->push('Dresses');
    });

Route::screen('dresses/upload', BulkUploadDressScreen::class)
    ->name('platform.dresses.upload')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->push('Dresses');
    });

Route::screen('dresses/edit/{dress?}', EditDressScreen::class)
    ->name('platform.dresses.edit')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->push('Dresses');
    });

Route::screen('dresses/view/{dress}', ViewSingleDressScreen::class)
    ->name('platform.dresses.detail')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->push('Dresses');
    });

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.dashboard.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.dashboard.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.dashboard.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.dashboard.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.dashboard.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.dashboard.advanced');

//Route::screen('idea', Idea::class, 'platform.screens.idea');

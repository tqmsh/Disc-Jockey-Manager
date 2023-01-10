<?php

declare(strict_types=1);

use Tabuna\Breadcrumbs\Trail;
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\EditEventScreen;
use App\Orchid\Screens\ViewEventScreen;
use App\Orchid\Screens\CreateEventScreen;
use App\Orchid\Screens\EditStudentScreen;
use App\Orchid\Screens\EmailSenderScreen;
use App\Orchid\Screens\ViewStudentScreen;
use App\Orchid\Screens\CreateStudentScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\ViewPendingStudentScreen;
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

//show add table screen for a event
Route::screen('/events/{event}/addTable', EditEventScreen::class)->name('platform.table.create');

//show pending student screen
Route::screen('/pendingstudents', ViewPendingStudentScreen::class)->name('platform.pendingstudent.list');


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

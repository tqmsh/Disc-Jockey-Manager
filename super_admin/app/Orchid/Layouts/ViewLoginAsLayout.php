<?php

namespace App\Orchid\Layouts;

use App\Models\Roles;
use App\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class ViewLoginAsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'users';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make()
                ->render(function($user) {
                    return Button::make('Login As')
                                ->icon('login')
                                ->type(Color::DEFAULT())
                                ->method('loginAsUser', ['user_id' => $user->id, 'portal' => $user->role]);
                }),
            
            TD::make('role', 'Role')
                ->render(function($user) {
                    return Roles::where('id', $user->role)->first()->name;
                }),

            TD::make('Name')
                ->render(function($user) {
                    return e($user->name);
                }),

            TD::make('First Name')
                ->render(function($user) {
                    return e($user->firstname);
                })
                ->defaultHidden(),

            TD::make('Last Name')
                ->render(function($user) {
                    return e($user->lastname);
                })
                ->defaultHidden(),
            
            TD::make('Email')
                ->render(function($user) {
                    return e($user->email);
                }),

            TD::make('Phone Number')
                ->render(function($user) {
                    return e($user->phonenumber == null || $user->phonenumber == "" ? "N/A" : $user->phonenumber);
                }),
        ];
    }
}

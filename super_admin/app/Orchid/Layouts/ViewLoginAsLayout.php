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
                ->render(function(User $user) {
                    return Button::make('Login As')
                                ->icon('login')
                                ->type(Color::DEFAULT())
                                ->method('loginAsUser', ['user_id' => $user->id, 'portal' => $user->role]);
                }),
            
            TD::make('role', 'Role')
                ->render(function(User $user) {
                    return Roles::where('id', $user->role)->first()->name;
                }),

            TD::make('name', 'Name'),

            TD::make('firstname', 'First Name')
                ->defaultHidden(),
            TD::make('lastname', 'Last Name')
                ->defaultHidden(),
            
            TD::make('email', 'Email'),

            TD::make('phonenumber', 'Phone Number')
                ->defaultHidden()
        ];
    }
}

<?php

namespace App\Orchid\Layouts;

use App\Models\Roles;
use App\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class ViewCompletedChecklistUsersLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'completed';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('checkboxes')
                ->render(function (User $user){
                    return CheckBox::make('users[]')
                        ->value($user->id)
                        ->checked(false);
                }),

            TD::make('full_name', 'Full Name')
                ->render(function(User $user) {
                    return $user->firstname . ' ' . $user->lastname;
                }),
            
            TD::make('email', 'Email'),

            TD::make('phonenumber', 'Phone Number')
                ->render(function(User $user) {
                    return is_null($user->phonenumber) ? 'None' : $user->phonenumber;
                }),

            TD::make('role', 'Role')
                ->render(function(User $user) {
                    return Roles::find($user->role)->slug;
                }),

            TD::make('')
                ->render(function(User $user) {
                    return Button::make('View Checklist Items')
                        ->method('redirect', ['checklistid' => request('checklist')->id, 'userid' => $user->id])
                        ->icon('eye')
                        ->type(Color::DARK());
                })
        ];
    }
}

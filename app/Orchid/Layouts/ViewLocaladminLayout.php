<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Localadmin;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;

class ViewLocaladminLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'Localadmin';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make()
                ->render(function (Localadmin $Localadmin){
                    return CheckBox::make('Localadmins[]')
                        ->value($Localadmin->id)
                        ->checked(false);
                }),
            TD::make('firstname', 'First Name')
                ->render(function (Localadmin $Localadmin) {
                    return Link::make($Localadmin->firstname)
                        ->route('platform.localadmin.edit', $Localadmin);
                }),
            TD::make('lastname', 'Last Name')
                ->render(function (Localadmin $Localadmin) {
                    return Link::make($Localadmin->lastname)
                        ->route('platform.localadmin.edit', $Localadmin);
                }),
            TD::make('phonenumber', 'Phone Number')
                ->render(function (Localadmin $Localadmin) {
                    return Link::make($Localadmin->phonenumber)
                        ->route('platform.localadmin.edit', $Localadmin);
                }),
            TD::make('email', 'Email')
                ->render(function (Localadmin $Localadmin) {
                    return Link::make($Localadmin->email)
                        ->route('platform.localadmin.edit', $Localadmin);
                }),
            TD::make('school', 'School')
                ->render(function (Localadmin $Localadmin) {
                    return Link::make($Localadmin->school)
                        ->route('platform.localadmin.edit', $Localadmin);
                }),
        ];
    }
}

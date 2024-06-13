<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\School;
use App\Models\Localadmin;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ViewPendingLocaladminLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'pending_localadmins';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {

        return [
            TD::make('checkboxes')
                ->render(function (Localadmin $Localadmin){
                    return CheckBox::make('localadmins[]')
                        ->value($Localadmin->user_id)
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
            
            TD::make('country', 'Country')
                ->render(function (Localadmin $Localadmin) {
                    return Link::make(School::find($Localadmin->school_id)->country)
                        ->route('platform.localadmin.edit', $Localadmin);
                }),

            TD::make('state_province', 'State/Province')
                ->render(function (Localadmin $Localadmin) {
                    return Link::make(School::find($Localadmin->school_id)->state_province)
                        ->route('platform.localadmin.edit', $Localadmin);
                }),

            TD::make('county', 'County')
                ->render(function (Localadmin $Localadmin) {
                    return Link::make(School::find($Localadmin->school_id)->county)
                        ->route('platform.localadmin.edit', $Localadmin);
                }),

        ];
    }
}

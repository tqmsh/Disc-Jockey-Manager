<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;

class ViewEventLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'events';


    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        $stateAbbreviations = [
            'Alabama' => 'AL',
            'Alaska' => 'AK',
            'Arizona' => 'AZ',
            'Arkansas' => 'AR',
            'California' => 'CA',
            'Colorado' => 'CO',
            'Connecticut' => 'CT',
            'Delaware' => 'DE',
            'Florida' => 'FL',
            'Georgia' => 'GA',
            'Hawaii' => 'HI',
            'Idaho' => 'ID',
            'Illinois' => 'IL',
            'Indiana' => 'IN',
            'Iowa' => 'IA',
            'Kansas' => 'KS',
            'Kentucky' => 'KY',
            'Louisiana' => 'LA',
            'Maine' => 'ME',
            'Maryland' => 'MD',
            'Massachusetts' => 'MA',
            'Michigan' => 'MI',
            'Minnesota' => 'MN',
            'Mississippi' => 'MS',
            'Missouri' => 'MO',
            'Montana' => 'MT',
            'Nebraska' => 'NE',
            'Nevada' => 'NV',
            'New Hampshire' => 'NH',
            'New Jersey' => 'NJ',
            'New Mexico' => 'NM',
            'New York' => 'NY',
            'North Carolina' => 'NC',
            'North Dakota' => 'ND',
            'Ohio' => 'OH',
            'Oklahoma' => 'OK',
            'Oregon' => 'OR',
            'Pennsylvania' => 'PA',
            'Rhode Island' => 'RI',
            'South Carolina' => 'SC',
            'South Dakota' => 'SD',
            'Tennessee' => 'TN',
            'Texas' => 'TX',
            'Utah' => 'UT',
            'Vermont' => 'VT',
            'Virginia' => 'VA',
            'Washington' => 'WA',
            'West Virginia' => 'WV',
            'Wisconsin' => 'WI',
            'Wyoming' => 'WY',
            'Alberta' => 'AB',
            'British Columbia' => 'BC',
            'Manitoba' => 'MB',
            'New Brunswick' => 'NB',
            'Newfoundland and Labrador' => 'NL',
            'Northwest Territories' => 'NT',
            'Nova Scotia' => 'NS',
            'Nunavut' => 'NU',
            'Ontario' => 'ON',
            'Prince Edward Island' => 'PE',
            'Quebec' => 'QC',
            'Saskatchewan' => 'SK',
            'Yukon' => 'YT',
        ];
        
        return [
            TD::make()
                ->render(function (Events $event){
                    return CheckBox::make('events[]')
                        ->value($event->id)
                        ->checked(false);
                }),

            TD::make()
                ->render(function($event){
                    return Button::make('Students')->method('redirect', ['event_id' => $event->id, 'type' => 'student'])->icon('people')->type(Color::DARK());
                }), 

            TD::make()
                ->render(function($event){
                    return Button::make('Bids')->method('redirect', ['event_id' => $event->id, 'type' => 'event'])->icon('dollar')->type(Color::PRIMARY());
                }),

            TD::make()
                ->render(function($event){
                    return Button::make('Promvote')->method('redirect', ['event_id' => $event->id, 'type' => 'promvote'])->icon('people')->type(Color::LIGHT());
                }), 

            TD::make()
                ->render(function (Events $event) {
                    return Button::make('Song Requests')
                        ->icon('music-tone-alt')         
                        ->method('redirect', ['event_id' => $event->id, 'type' => 'songReq'])
                        ->type(Color::INFO());
                        // ->class('blue', 'responsive-button', 'button');
                }),


            TD::make('event_name', 'Event Name')
                ->render(function (Events $event) {
                    return $event->event_name;
                })->width('45%'),

            TD::make('school', 'School')
                ->render(function (Events $event) {
                    return $event->school;
                })->width('75%'),

            TD::make('state_province', 'State')
                ->render(function (Events $event) use ($stateAbbreviations) {
                    $stateName = $event->school_1->state_province;
                    $abbreviation = $stateAbbreviations[$stateName] ?? $stateName;

                    return $abbreviation;
                })->width('10px'),

                
            TD::make()
                ->render(function (Events $event) {
                    return Button::make('Edit')-> type(Color::PRIMARY())->  method('redirect', ['event'=>$event->id, 'type'=>"edit"]) ->icon('pencil');
                }),
        ];    
    }
}



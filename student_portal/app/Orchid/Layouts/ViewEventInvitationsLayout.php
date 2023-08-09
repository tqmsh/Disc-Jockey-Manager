<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;
use Orchid\Support\Color;

class ViewEventInvitationsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'eventInvitations';


    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            
            TD::make('accept', 'Accept')
                ->render(function (Events $event) {
                    return Button::make('Accept')
                        ->icon('check')
                        ->method('updateInvitationStatus')
                        ->confirm('Are you sure you want to accept this invitation?')
                        ->type(Color::SUCCESS())
                        ->parameters([
                            'event_id' => $event->id,
                            'invitation_status' => 1,
                        ]);
                }),

            TD::make('decline', 'Reject')
                ->render(function (Events $event) {
                    return Button::make('Reject')
                        ->icon('close')
                        ->method('updateInvitationStatus')
                        ->confirm('Are you sure you want to decline this invitation?')
                        ->type(Color::DANGER())
                        ->parameters([
                            'event_id' => $event->id,
                            'invitation_status' => 2,
                        ]);
                }),

            TD::make('event_name', 'Event Name')
                ->render(function (Events $event) {
                    return e($event->event_name);
                }),
            TD::make('event_start_time', 'Event Start Date')
                ->render(function (Events $event) {
                    return e($event->event_start_time);
                }),
            TD::make('school', 'School')
                ->render(function (Events $event) {
                    return e($event->school);
                }),
            TD::make('event_address', 'Event Address')
                ->render(function (Events $event) {
                    return e($event->event_address);
                }),
            TD::make('event_zip_postal', 'Event Zip/Postal')
                ->render(function (Events $event) {
                    return e($event->event_zip_postal);
                }),
            TD::make('event_info', 'Event Info')
                ->render(function (Events $event) {
                    return e($event->event_info);
                }),

            TD::make('event_rules', 'Event Rules')
                ->render(function (Events $event) {
                    return e($event->event_rules);
                }),
        ];    
    }
}

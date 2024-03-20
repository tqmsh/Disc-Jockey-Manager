<?php

namespace App\Orchid\Layouts;

use App\Models\CoupleRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\TD;
use App\Models\Events;
use Orchid\Support\Color;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use App\Models\EventAttendees;

class ViewSinglesLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'singles';


    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('profile', '')
                ->render(function(EventAttendees $user){
                    $pfp = User::find($user->user_id)->pfp;
                    return "<img src='$pfp' width='100'>";
                }),
            TD::make('person_name', 'Name')
                ->render(function (EventAttendees $user) {
                    return e(User::find($user->user_id)->fullName());
                }),
            TD::make('event_name', 'Event Name')
                ->render(function(EventAttendees $user) {
                    // return e($event->event_name);
                    $event = Events::find($user->event_id);

                    return Button::make($event->event_name)
                        ->type(Color::LIGHT())
                        ->method('redirect', ['event_id' => $event->id]);
                }),
            TD::make()
                ->width('100px')
                ->align(TD::ALIGN_RIGHT)
                ->render(function(EventAttendees $user){
                    $self = Auth::user()->id;
                    $other = $user->user_id;
                    $event = $user->event_id;
                    $request_outgoing = CoupleRequest::where("owner_user_id",$self)
                        ->where("receiver_user_id",$other)
                        ->where("event_id", $event)
                        ->pluck("id")
                        ->toArray();
                    $request_incoming = CoupleRequest::where("owner_user_id",$other)
                        ->where("receiver_user_id",$self)
                        ->where("event_id", $event)
                        ->pluck("id")
                        ->toArray();
                    if(count($request_outgoing) == 1){ // Sent a request already
                        return Button::make('Retract Request')
                            ->type(Color::DANGER())
                            ->method('retract_request', ['request_id'=>$request_outgoing])
                            ->confirm(__('Are you sure you want to retract request?'))
                            ->icon('close');
                    } elseif (count($request_incoming) == 1){ // Other user already sent a request
                        return Button::make('Accept Request')
                            ->type(Color::INFO())
                            ->method('accept_request', ['request_id'=>$request_incoming])
                            ->icon('check');
                    } else {
                        return Button::make('Send Request')
                            ->type(Color::WARNING())
                            ->method('send_request', ['event_id' => $user->event_id, 'user_id' => $user->user_id])
                            ->icon('plus');
                    }
                })
        ];
    }
}

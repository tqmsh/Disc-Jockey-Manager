<?php

namespace App\Orchid\Screens;


use App\Models\User;
use App\Models\Events;
use App\Models\Election;
use Orchid\Screen\Screen;
use Illuminate\Support\Arr;
use App\Models\EventAttendees;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewEventLayout;
use App\Notifications\GeneralNotification;
use App\Orchid\Layouts\ViewRegisteredEventLayout;
use App\Orchid\Layouts\ViewEventInvitationsLayout;
use App\Http\Controllers\EventAttendeesController;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalclient;

class ViewEventScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $registered_event_ids = EventAttendees::where('user_id', Auth::user()->id)->where('invitation_status', 1)->get('event_id')->toArray();
        $registered_event_ids = Arr::pluck($registered_event_ids, ['event_id']);
        $invitedEvents = EventAttendees::where('user_id', Auth::id())->where('invitation_status', 0)->where('invited', 1)->get('event_id');

        return [
            'events' => Events::where('school_id', Auth::user()->student->school_id)->latest('events.created_at')->paginate(10),
            'registered_events' => Events::whereIn('id', $registered_event_ids)->latest('events.created_at')->paginate(10),
            'eventInvitations' => Events::whereIn('id', $invitedEvents)->latest('events.created_at')->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Events';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Contact Your Prom Committees')
                ->icon('comment')
                ->route('platform.contact-prom-committees'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list')
        ];
    }


    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
          Layout::tabs([
                'All Events' => [
                    ViewEventLayout::class,
                ],
                'Your Registered Events' => [
                    ViewRegisteredEventLayout::class
                ],
                'Event Invitations' => [
                    ViewEventInvitationsLayout::class
                ]
            ]),
        ];
    }

    public function updateInvitationStatus($event_id, $invitation_status){
        $eventAttendee = EventAttendees::where('event_id', $event_id)->where('user_id', Auth::user()->id)->whereNot('invitation_status', 1)->first();
        $eventAttendee->invitation_status = $invitation_status;
        $eventAttendee->save();
        $event = Events::find($event_id);
        $event_inviter = User::find($eventAttendee->inviter_user_id);

        //send notification to event creator that user has accepted invitation
        if($invitation_status == 1){

            $event_inviter->notify(new GeneralNotification([
                'title' => 'Event Invitation Accepted',
                'message' => Auth::user()->firstname . ' ' .Auth::user()->lastname . ' has accepted your invitation to ' . $event->event_name . '.',
                'action' => '/admin/events/students/' . $event_id,
            ]));
        } else{
            $event_inviter->notify(new GeneralNotification([
                'title' => 'Event Invitation Declined',
                'message' => Auth::user()->firstname . ' ' .Auth::user()->lastname . ' has declined your invitation to ' . $event->event_name . '.',
                'action' => '/admin/events/students/' . $event_id,
            ]));
        }

        Toast::info('Invitation status updated');
        return redirect()->route('platform.event.list');
    }

    public function redirect($event_id, $type){
        $type = request('type');
        if($type == 'table'){
            return redirect()->route('platform.event.tables', $event_id);
        }   
        else if($type == 'songs'){
            return redirect()->route('platform.songs.list', $event_id);
        }
        else if ($type == 'ticketBought') {
            return redirect()->route('platform.event.list');
        }
        else if ($type == 'eventInformation') {
            return redirect()->route('platform.event.information', $event_id);

        }
        else if($type == 'election'){
            $election = Election::where('event_id',$event_id)->first();

            // Election exists
            if ($election != null){
                // Election hasnt started
                if (now() < $election->start_date)
                {
                    Toast::warning('This election has not started yet.');
                    return redirect()->route('platform.event.list');
                }
                // Ongoing or ended election
                else
                {
                    return redirect()->route('platform.election.list', $event_id);
                }
            }
            // Election doesnt exist
            else{
                Toast::warning('An election is not yet created, speak to supervisor to open an election');
            }
        } else if($type == 'food'){
            return redirect()->route('platform.eventFood.list', $event_id);
        }

        return redirect()->route('platform.event.register', $event_id);   
    }

    public function payment(Request $request, Events $event, $event_id) {
        $provider = new PaypalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $event = Events::find($event_id);

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                // Success function gets called here
                "return_url" => route('paypal_success', ['event_id' => $event_id]),
                "cancel_url" => route('paypal_cancel')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "CAD",
                        "value" => $event->ticket_price,
                    ]
                ]
            ]
        ]); 

        if(isset($response['id']) && $response['id']!=null) {
            foreach($response['links'] as $link) {
                if($link['rel'] === 'approve') {
                    return redirect($link["href"]);
                }
            }
        } else {
            return redirect()->route('paypal_cancel');
        }
        
    }

    public function success(Request $request, $event_id) {

        $provider = new PaypalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response =  $provider->capturePaymentOrder($request->token);

        // Last check to see if the Payment went trough successfully
        if(isset($response['status']) && $response['status'] == 'COMPLETED') {

            EventAttendees::where('user_id', Auth::user()->id)
                                    ->where('event_id', $event_id)
                                    ->update(array('ticketstatus' => 'paid'));
                        

            Toast::success('Tickets bought Successfully!');

            $user = Auth::user();

            $requestData = new Request();
            $requestData->setUserResolver(function () use ($user) {
                return $user;
            });

            $requestData->merge([
                'attendee_id' => strval(Auth::user()->id),
                'event_id' => $event_id
            ]);

            $eventAttendeesController = new EventAttendeesController();
            $response_1 = $eventAttendeesController->createCode($requestData);

            return redirect()->route('platform.event.list');

        } else { 
            return redirect()->route('paypal_cancel');

        }
    }

    public function cancel() {
        Toast::error('Error');
    }
}
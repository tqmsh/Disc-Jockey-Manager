<?php

namespace App\Orchid\Screens;
use App\Models\Couple;
use App\Models\CoupleRequest;
use App\Models\EventAttendees;
use App\Models\Events;
use App\Orchid\Layouts\EditCoupleParamsLayout;
use App\Orchid\Layouts\ViewCouplesLayout;
use App\Orchid\Layouts\ViewSinglesLayout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PromDateScreen extends Screen
{

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $now = now()->format("Y-m-d H:i:s");
        $attended_events = EventAttendees::where("user_id", Auth::id())->get("event_id")->toArray();
        $events = array_unique(Events::whereIn('id', $attended_events)
            ->where('event_finish_time', '>=', $now)->get("id")->toArray());
        // List of everyone in events
        $attendees = EventAttendees::whereIn("event_id", $events)
            ->pluck("user_id")
            ->toArray();
        $couples_users_1 = Couple::whereIn("event_id", $events)
            ->pluck("student_user_id_1")
            ->toArray();
        $couples_users_2 = Couple::whereIn("event_id", $events)
            ->pluck("student_user_id_2")
            ->toArray();
        $couples_users = array_merge($couples_users_1,$couples_users_2);
        $singles = array_diff($attendees, $couples_users);
        $singles = array_diff($singles, [Auth::user()->id]); // They're single, no doubt, but they can't request themselves...
        $singles = EventAttendees::whereIn("user_id", $singles)
            ->where("invitation_status", 1)
            ->get();
        $couples = Couple::whereIn("event_id", $events)
            ->get();
        $user_id = Auth::user()->id;
        $current_couple = Couple::where('student_user_id_1',$user_id)->orWhere('student_user_id_2',$user_id)->first();
        if($current_couple) {
            return [
                "singles" => $singles,
                "couples" => $couples,
                "couple_name" => $current_couple->couple_name,
                "status" => $current_couple->status,
                "description" => $current_couple->description,
                "couple_id" => $current_couple->id,
            ];
        } else{
            return [
                "singles" => $singles,
                "couples" => $couples
            ];
        }
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'PromDate';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $user_id = Auth::user()->id;
        $user_in_couple = count(Couple::where('student_user_id_1',$user_id)->orWhere('student_user_id_2',$user_id)->pluck('id')) == 1;
        if ($user_in_couple) {
            return [
                Layout::tabs([
                    "Singles" => ViewSinglesLayout::class,
                    "Couples" => ViewCouplesLayout::class,
                    "Couple Info" => EditCoupleParamsLayout::class
                ])
            ];
        } else {
            return [
                Layout::tabs([
                    "Singles" => ViewSinglesLayout::class,
                    "Couples" => ViewCouplesLayout::class
                ])
            ];
        }
    }

    public function redirect($event_id){
        return redirect()->route('platform.event.information', $event_id);
    }

    public function redirect_2($couple_id){
        return redirect()->route('platform.couple', $couple_id);
    }

    public function send_request($event_id, $user_id)
    {
        $request = CoupleRequest::create([
            "owner_user_id" => Auth::user()->id,
            "receiver_user_id"=> $user_id,
            "event_id"=> $event_id
        ]);
        Toast::success('Request has been sent.');
    }
    public function accept_request($request_id)
    {
        // Add couple
        $request = CoupleRequest::find($request_id);
        $couple = Couple::create([
            "student_user_id_1" => $request->owner_user_id,
            "student_user_id_2" => $request->receiver_user_id,
            "event_id" => $request->event_id
        ]);
        $request->destroy();
        Toast::success('Request has been accepted.');
    }

    public function retract_request($request_id)
    {
        // Delete request
        CoupleRequest::destroy($request_id);
        Toast::success('Request has been retracted.');
    }

    public function update(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            $couple = Couple::where('student_user_id_1',$user_id)->orWhere('student_user_id_1',$user_id)->first();
            $couple = $couple->fill([
                "couple_name" => $request->input("couple_name"),
                "status" => $request->input("status"),
                "description" => $request->input("description"),
            ])->save();

            if ($couple) {
                Toast::success('Info updated successfully!');
                return redirect()->route('platform.promdate');
            } else {
                Alert::error('Error: Info not updated for an unknown reason.');
            }
        } catch (Exception $e) {
            Alert::error('Error: ' . $e->getMessage());
        }
    }

    public function breakup($couple_id)
    {
        // Delete couple
        Couple::destroy($couple_id);
        Toast::success('Couple has been broken up.');
    }
}

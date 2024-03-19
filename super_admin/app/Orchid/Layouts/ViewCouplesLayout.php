<?php

namespace App\Orchid\Layouts;

use App\Models\Couple;
use App\Models\CoupleRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\TD;
use App\Models\Events;
use Orchid\Support\Color;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;
use App\Models\EventAttendees;

class ViewCouplesLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'couples';


    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('profile1', '')
                ->render(function(Couple $couple){
                    $pfp = User::find($couple->student_user_id_1)->pfp;
                    return "<img src='$pfp' width='100'>";
                }),
            TD::make('person_name1', 'Name')
                ->render(function (Couple $couple) {
                    return e(User::find($couple->student_user_id_1)->fullName());
                }),
            TD::make('profile2', '')
                ->render(function(Couple $couple){
                    $pfp = User::find($couple->student_user_id_2)->pfp;
                    return "<img src='$pfp' width='100'>";
                }),
            TD::make('person_name2', 'Name')
                ->render(function (Couple $couple) {
                    return e(User::find($couple->student_user_id_2)->fullName());
                }),
            TD::make('event_name', 'Event Name')
                ->render(function(Couple $couple) {
                    // return e($event->event_name);
                    $event = Events::find($couple->event_id);

                    return Button::make($event->event_name)
                        ->type(Color::LIGHT())
                        ->method('redirect', ['event_id' => $event->id]);
                }),
            TD::make("couple_details", "")
                ->render(function (Couple $couple){
                    return Button::make("Couple Info")
                        ->type(Color::INFO())
                        ->method("redirect_2", ["couple_id"=>$couple->id]);
                })
        ];
    }
}

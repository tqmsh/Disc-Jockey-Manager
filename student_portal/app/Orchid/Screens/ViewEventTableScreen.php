<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Seating;
use Orchid\Screen\Sight;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\EventAttendees;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Facades\Alert;
use Termwind\Components\Dd;

class ViewEventTableScreen extends Screen
{
    public $event;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        return [
            'event' => $event,
            'tables' => Seating::where('event_id', $event->id)->paginate(10),
            'student_table' => Seating::find(EventAttendees::where('user_id', Auth::user()->id)->where('event_id', $event->id)->pluck('table_id')->first()) ?? null,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Tables for: ' . $this->event->event_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
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

                'All Tables' => [

                    Layout::table('tables', [

                        TD::make()
                            ->align(TD::ALIGN_LEFT)
                            ->render(function(Seating $table){
                                return Button::make('Request to be Seated Here')->type(Color::PRIMARY())->icon('table')->method('requestSeat', ['requested_table_id' => $table->id]);
                            }), 

                        TD::make('Table Name')
                            ->align(TD::ALIGN_LEFT)
                            ->render(function (Seating $table) {
                                return e($table->tablename);
                            })->width('30%'),

                        TD::make('Seated Students')
                            ->align(TD::ALIGN_LEFT)
                            ->render(function (Seating $table) {
                                return $this->getNames($table->id);
                            })->width('60%'),
                    ]),
                ],

                'Your Table' => [

                    Layout::legend('student_table',[

                        Sight::make('tablename', 'Table Name'),
                        Sight::make('seated_students', 'Seated Students')->render(function($table){
                            return $this->getNames($table->id);
                        }),

                    ]),
                ],
                
            ]),
        ];
    }

    //used for getting the names of the students at a table
    private function getNames($tableId)
    {
        $students = User::whereIn('id', EventAttendees::where('table_id', $tableId)->pluck('user_id'))->get();
        $names = [];

        foreach ($students as $student) {
            $names[] = $student->firstname;
        }
        
        return implode(", ", $names);
    }

    //used for requesting to be seated at a table
    public function requestSeat(Events $event)
    {
        try{
            
            $table = Seating::find(request('requested_table_id'));

            //check if the table is full
            if($table->capacity == 0){
                Toast::error('This table is full.');
                return redirect()->route('platform.event.tables');
            }

            //check if the student has already requested to be seated at this table
            if(EventAttendees::where('user_id', Auth::user()->id)->where('event_id', $event->id)->where('table_id', $table->id)->where('approved', 0)->exists()){
                Toast::info('You have already requested to be seated at this table. Please wait for a admin to approve your request.');
                return redirect()->route('platform.event.tables');
            }

            //insert the request into the database approved defaults to 0
            EventAttendees::create([
                'user_id' => Auth::user()->id,
                'event_id' => $event->id,
                'table_id' => $table->id,
            ]);

            Toast::success('Your request to be seated at this table has been sent to the admin. Please wait till they approve or reject your request.');

        }catch(Exception $e){
            Alert::error('There was a problem requesting to be seated at this table. ' . $e->getMessage());
            return redirect()->route('platform.event.list');
        }

    }
}

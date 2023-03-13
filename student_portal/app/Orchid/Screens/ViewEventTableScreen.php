<?php

namespace App\Orchid\Screens;

use App\Models\User;
use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Seating;
use App\Models\Student;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\EventAttendees;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;

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

                        //!DOESNT WORK YET
                        TD::make()
                            ->align(TD::ALIGN_LEFT)
                            ->render(function($event){
                                return Button::make('Request to be Seated Here')->type(Color::PRIMARY())->icon('table');
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
                            })->width('40%'),
                    ]),
                ],

                // 'Your Table' => [
                //     Layout::table('seatedStudents', [



                //     ])
                // ],


                // 'Add Students to Tables' => [
                //     Layout::table('unseatedStudents', [
                //     ])                
                // ],
                
            ]),
        ];
    }

    private function getNames($id)
    {
        $students = User::whereIn('id', EventAttendees::where('table_id', $id)->pluck('user_id'))->get();
        $names = [];

        foreach ($students as $student) {
            $names[] = $student->firstname;
        }
        
        return implode(", ", $names);
    }
}

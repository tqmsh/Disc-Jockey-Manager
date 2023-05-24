<?php

namespace App\Orchid\Screens;

use Locale;
use Exception;
use App\Models\User;
use App\Models\Events;
use App\Models\Student;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewRequestersLayout;
use App\Models\SongRequest;

class ViewRequestersScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(array $requesters): iterable
    {
        return [
            'requesters' => $requesters
        ];
    }


    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Requesters';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

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
            Layout::rows([

                Group::make([

                    Select::make('sort_option')
                        ->title('Order Students By:')
                        ->empty('No selection')
                        ->help('Start typing in boxes to search')
                        ->options([
                            'firstname' => 'First Name',
                            'lastname' => 'Last Name',
                            'grade' => 'Grade'
                        ]),

                    Select::make('event_id')
                        ->title('Event:')
                        ->empty('No selection')
                        ->fromQuery(Events::query()->where('school_id', Localadmin::where('user_id', Auth::user()->id)->get('school_id')->value('school_id')), 'event_name'),

                    Select::make('ticketstatus')
                        ->title('Ticket Status')
                        ->empty('No selection')
                        ->options([
                            'Paid' => 'Paid',
                            'Unpaid' => 'Unpaid'
                        ]),
                ]),
                    
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),
                
            ViewRequestersLayout::class
        ];
    }


}
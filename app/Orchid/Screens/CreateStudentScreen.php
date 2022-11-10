<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Events;
use App\Models\School;
use App\Models\Student;
use Orchid\Screen\Screen;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;

class CreateStudentScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Add a New Student';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Add')
                ->icon('plus')
                ->method('createStudent'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.student.list')
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

                Input::make('firstname')
                    ->title('First Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. John'),

                Input::make('lastname')
                    ->title('Last Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Doe'),

                Input::make('name')
                    ->title('Username')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. KingKhan435'),

                Input::make('phonenumber')
                    ->title('Phone Number')
                    ->type('text')
                    ->mask('(999) 999-9999')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. (613) 859-5863'),

                Input::make('email')
                    ->title('Email')
                    ->type('email')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. johndoe@gmail.com'),

                Input::make('password')
                    ->title('Password')
                    ->type('password')
                    ->required()
                    ->horizontal(),

                Select::make('school')
                    ->title('School')
                    ->required()
                    ->empty('No Selection')
                    ->horizontal()
                    ->fromModel(School::class, 'school_name', 'school_name'),

                Select::make('country')
                    ->title('Country')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country'),

                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->empty('No Selection')
                    ->fromModel(School::class, 'state_province', 'state_province'),

                Select::make('school_board')
                    ->title('School Board')
                    ->horizontal()
                    ->empty('No Selection')
                    ->fromModel(School::class, 'school_board', 'school_board'),

                Select::make('grade')
                    ->title('Grade')
                    ->horizontal()
                    ->empty('No Selection')
                    ->options([
                        '9' => 9,
                        '10' => 10,
                        '11' => 11,
                        '12' => 12,
                    ]),

                Select::make('event_id')
                    ->title('Event')
                    ->horizontal()
                    ->empty('No Selection')
                    ->fromModel(Events::class, 'id'),

                Input::make('allergies')
                    ->title('Allergies')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Peanuts'),
            ]),
        ];
    }


    public function createStudent(Request $request){

        try{

            $studentTableFields = [
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'email' => $request->input('email'),
                'phonenumber' => $request->input('phonenumber'),
                'school' => $request->input('school'),
                'grade' => $request->input('grade'),
                'event_id' => $request->input('event_id'),
                'allergies' => $request->input('allergies'),
                'user_id' => null,
            ];

            $userTableFields = [
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'name' => $request->input('name'),
                'country' => $request->input('country'),
                'phonenumber' => $request->input('phonenumber'),
                'remember_token' => Str::random(10),
                'role' =>'student',
            ];


            //check for duplicate email
            if(count(User::where('email', $request->input('email'))->get()) == 0){
                
                //no duplicates found
                User::create($userTableFields);

                $studentTableFields['user_id'] = User::where('email', $request->input('email'))->get('id')->value('id');
                
                Student::create($studentTableFields);
                
                Toast::success('Student Added Succesfully');
                return redirect()->route('platform.student.list');
            
            }else{
                //duplicate email found
                Toast::error('Email already exists.');
            }

        }catch(Exception $e){

            Alert::error('There was an error creating this school. Error Code: ' . $e);
        }
    }
}

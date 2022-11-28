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
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Fields\Relation;
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

                Password::make('password')
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
                    ->required()
                    ->fromModel(School::class, 'country', 'country'),

                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->empty('No Selection')
                    ->required()
                    ->fromModel(School::class, 'state_province', 'state_province'),

                Select::make('county')
                    ->title('County')
                    ->horizontal()
                    ->required()
                    ->empty('No Selection')
                    ->fromModel(School::class, 'county', 'county'),

                Select::make('grade')
                    ->title('Grade')
                    ->horizontal()
                    ->required()
                    ->empty('No Selection')
                    ->options([
                        '9' => 9,
                        '10' => 10,
                        '11' => 11,
                        '12' => 12,
                    ]),

                Relation::make('event_id')
                    ->title('Event')
                    ->horizontal()
                    ->displayAppend('full')
                    ->empty('No Selection')
                    ->fromModel(Events::class, 'id'),

                Select::make('ticketstatus')
                    ->title('Ticket Status')
                    ->required()
                    ->horizontal()
                    ->options([
                        'Unpaid' => 'Unpaid',
                        'Paid'   => 'Paid',
                    ]),

                Input::make('allergies')
                    ->title('Allergies')
                    ->type('text')
                    ->horizontal()
                    ->placeholder('Ex. Peanuts'),
            ]),
        ];
    }


    public function createStudent(Request $request){

        try{

            $studentTableFields = $this->getStudentFields($request);

            $userTableFields = $this->getUserFields($request);

            //check for duplicate email
            if($this->validEmail($request)){
                
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

            Alert::error('There was an error creating this school. Error Code: ' . $e->getMessage());
        }
    }

    //check for duplicate emails
    private function validEmail($request){
        return count(User::where('email', $request->input('email'))->get()) == 0;
    }

    //this functions returns the values that need to be inserted in the localadmin table in the db
    private function getStudentFields($request){
        $school_id = School::where('school_name', $request->input('school'))
                            ->where('county', $request->input('county'))
                            ->where('state_province', $request->input('state_province'))
                            ->where('country', $request->input('country'))
                            ->get('id')->value('id');

        if(is_null($school_id)){
            throw New Exception('You are trying to enter a invalid school');
        }

        $studentTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'phonenumber' => $request->input('phonenumber'),
            'school' => $request->input('school'),
            'school_id' => $school_id,
            'grade' => $request->input('grade'),
            'account_status' =>1,
            'event_id' => $request->input('event_id'),
            'allergies' => $request->input('allergies'),
            'ticketstatus'=> $request->input('ticketstatus'),
            'user_id' => null,
        ];
        
        return $studentTableFields;
    }

    //this functions returns the values that need to be inserted in the user table in the db
    private function getUserFields($request){

        $userTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'name' => $request->input('name'),
            'country' => $request->input('country'),
            'account_status' => 1,
            'permissions' =>'{"platform.index":true}',
            'phonenumber' => $request->input('phonenumber'),
            'remember_token' => Str::random(10),
            'role' =>'student',
        ];
        
        return $userTableFields;
    }
}

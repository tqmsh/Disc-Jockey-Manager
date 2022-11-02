<?php

namespace App\Orchid\Screens;

use App\Models\User;
use App\Models\Events;
use App\Models\School;
use App\Models\Student;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class EditStudentScreen extends Screen
{
    public $student;
    public $school;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Student $student): iterable
    {
        return [
            'student' => $student
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Student: ' . $this->student->firstname . ' ' . $this->student->lastname;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Submit')
                ->icon('check')
                ->method('update'),

            Button::make('Delete Student')
                ->icon('trash')
                ->method('delete'),

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
        $this->school = $this->student->getSchool($this->student->school);

        return [

            Layout::rows([

                Input::make('firstname')
                    ->title('First Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->student->firstname),

                Input::make('lastname')
                    ->title('Last Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->student->lastname),

                Input::make('email')
                    ->title('Email')
                    ->type('email')
                    ->required()
                    ->horizontal()
                    ->value($this->student->email),
                
                Select::make('school')
                    ->title('School')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'school_name', 'school_name')
                    ->value($this->school->value('school_name')),

                Select::make('country')
                    ->title('Country')
                    ->required()
                    ->horizontal()
                    ->fromModel(School::class, 'country', 'country')
                    ->value($this->student->getUser($this->student->email)->value('country')),

                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->fromModel(School::class, 'state_province', 'state_province')
                    ->value($this->school->value('state_province')),


                Select::make('school_board')
                    ->title('School Board')
                    ->horizontal()
                    ->fromModel(School::class, 'school_board', 'school_board')
                    ->value($this->school->value('school_board')),
                   
                Input::make('grade')
                    ->title('Grade')
                    ->type('number')
                    ->required()
                    ->horizontal()
                    ->value($this->student->grade),

                Input::make('phonenumber')
                    ->title('Phone Number')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->mask('(999) 999-9999')
                    ->placeholder('Ex. (613) 859-5863')
                    ->value($this->student->phonenumber),

                Select::make('event_id')
                    ->title('Event ID')
                    ->horizontal()
                    ->fromModel(Events::class, 'id')
                    ->value($this->student->event_id),

                Select::make('ticketstatus')
                    ->title('Ticket Status')
                    ->required()
                    ->horizontal()
                    ->value($this->student->ticketstatus)
                    ->options([
                        'Paid'   => 'Paid',
                        'Unpaid' => 'Unpaid',
                    ]),

                Input::make('allergies')
                    ->title('Allergies')
                    ->type('text')
                    ->horizontal()
                    ->value($this->student->allergies),               
            ]),
        ];
    }

    public function update(Student $student, Request $request)
    {

        //!PUT ALL THIS CODE IN A TRY CATCH
        //!CHECK IF SCHOOL BOARD MATCHES THE SCHOOL BEFORE UPDATING

        $studentTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'grade' => $request->input('grade'),
            'phonenumber' => $request->input('phonenumber'),
            'ticketstatus' => $request->input('ticketstatus'),
            'school' => $request->input('school'),
            'event_id' => $request->input('event_id'),
            'allergies' => $request->input('allergies'),
        ];

        $userTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'country' => $request->input('country'),
            'status' => $request->input('ticketstatus'),
        ];

        //check for duplicate
        if(count(User::whereNot('id', $student->user_id)->where('email', $request->input('email'))->get()) == 0){
            
            //email not changed
            $student->update($studentTableFields);
            
            User::where('id', $student->user_id)->update($userTableFields);
            
            Alert::success('You have successfully updated ' . $request->input('firstname') . ' ' . $request->input('lastname') . '.');

            return redirect()->route('platform.student.list');
          
        }else{
            //duplicate email found
            Alert::error('Email already exists.');
        }
    } 

    public function delete(Student $student)
    {
        //!PUT ALL THIS CODE IN A TRY CATCH
        $student->delete();

        Alert::info('You have successfully deleted the student.');

        return redirect()->route('platform.student.list');
    }
}

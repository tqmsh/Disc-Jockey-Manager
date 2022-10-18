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

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Student $student): iterable
    {
        return [
            'student' => $student,
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
                ->icon('plus')
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
                    ->empty($this->student->school == null ? 'No selection' : $this->student->school)
                    ->fromModel(School::class, 'school_name', 'school_name'),

                Select::make('country')
                    ->title('Country')
                    ->required()
                    ->horizontal()
                    ->empty($this->student->getCountry($this->student->user_id) == null ? 'No selection' : $this->student->getCountry($this->student->user_id))
                    ->fromModel(School::class, 'country', 'country'),

                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->empty()
                    ->fromModel(School::class, 'state_province', 'state_province'),

                Select::make('school_board')
                    ->title('School Board')
                    ->horizontal()
                    ->empty()
                    ->fromModel(School::class, 'school_board', 'school_board'),
                    
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
                    ->value($this->student->phonenumber),

                Select::make('event_id')
                    ->title('Event ID')
                    ->horizontal()
                    ->empty($this->student->event_id == null ? 'No selection' : $this->student->event_id)
                    ->fromModel(Events::class, 'id'),

                Select::make('ticketstatus')
                    ->title('Ticket Status')
                    ->required()
                    ->horizontal()
                    ->empty($this->student->ticketstatus == null ? 'No selection' : $this->student->ticketstatus)
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


        $student->update($studentTableFields);
        
        User::where('id', $student->user_id)->update($userTableFields);
        
        Alert::info('You have successfully updated the student.');

        return redirect()->route('platform.student.list');
    }

    public function delete(Student $student)
    {
        $student->delete();

        Alert::info('You have successfully deleted the student.');

        return redirect()->route('platform.student.list');
    }
}

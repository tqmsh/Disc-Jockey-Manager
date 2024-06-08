<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Student;
use Orchid\Screen\Screen;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        abort_if(Localadmin::where('user_id', Auth::user()->id)->first()->school_id != $student->school_id, 403, 'You are not authorized to view this page.');
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

                Select::make('allergies')
                    ->title('Allergies')
                    ->horizontal()
                    ->allowAdd()
                    ->value($this->student->allergies)
                    ->options([
                        'Peanuts' => 'Peanuts',
                        'Tree Nuts' => 'Tree Nuts',
                        'Shellfish' => 'Shellfish',
                        'Milk' => 'Milk',
                        'Eggs' => 'Eggs',
                        'Wheat' => 'Wheat',
                        'Soy' => 'Soy',
                        'Fish' => 'Fish',
                    ]),                           
            ]),
        ];
    }

    public function update(Student $student, Request $request)
    {
        try{
            $request->validate([
                'firstname' => 'required|max:255',
                'lastname' => 'required|max:255',
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($student->user_id),
                ],
                'grade' => 'required|integer|in:9,10,11,12',
                'phonenumber' => 'required|max:20',
                'allergies' => 'nullable|max:255',
            ]);
            $studentTableFields = $this->getStudentFields($request);

            $userTableFields = $this->getUserFields($request);

            //email not changed
            $student->update($studentTableFields);
            
            User::where('id', $student->user_id)->update($userTableFields);
            
            Toast::success('You have successfully updated: ' . $request->input('firstname') . ' ' . $request->input('lastname') . '.');

            return redirect()->route('platform.student.list');

        }catch(Exception $e){

            Alert::error('There was an error editing this student. Error Code: ' . $e->getMessage());
        }
    } 

    public function delete(Student $student)
    {
        try{

            User::where('id', $student->user_id)->delete();

            Toast::info('You have successfully deleted the student.');

            return redirect()->route('platform.student.list');

        }catch(Exception $e){
            
            Alert::error('There was an error deleting this student. Error Code: ' . $e->getMessage());
        }
    }

    //this functions returns the values that need to be inserted in the localadmin table in the db
    private function getStudentFields($request){

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
        
        return $studentTableFields;
    }

    //this functions returns the values that need to be inserted in the user table in the db
    private function getUserFields($request){

        $userTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'phonenumber' => $request->input('phonenumber'),
        ];
        
        return $userTableFields;
    }
}

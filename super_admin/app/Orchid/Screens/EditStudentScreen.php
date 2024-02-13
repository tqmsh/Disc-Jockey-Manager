<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\Student;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
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
        $this->school = School::find($this->student->school_id);

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
                    ->empty('Start typing to Search...')
                    ->fromModel(School::class, 'school_name', 'school_name')
                    ->value($this->school->school_name),

                Select::make('country')
                    ->title('Country')
                    ->required()
                    ->horizontal()
                    ->empty('Start typing to Search...')
                    ->fromModel(School::class, 'country', 'country')
                    ->value(User::find($this->student->user_id)->country),

                Select::make('state_province')
                    ->title('State/Province')
                    ->horizontal()
                    ->empty('Start typing to Search...')
                    ->fromModel(School::class, 'state_province', 'state_province')
                    ->value($this->school->state_province),

                Select::make('county')
                    ->title('County')
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'county', 'county')
                    ->value($this->school->county),

                Select::make('city_municipality')
                    ->title('City/Municipality')
                    ->empty('Start typing to Search...')
                    ->horizontal()
                    ->fromModel(School::class, 'city_municipality', 'city_municipality')
                    ->value($this->school->city_municipality),
                   
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
                    ->empty('Start typing to Search...')
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

            $studentTableFields = $this->getStudentFields($request);

            $userTableFields = $this->getUserFields($request);

            //check for duplicate
            if($this->validEmail($request, $student)){
                
                //email not changed
                $student->update($studentTableFields);
                
                User::where('id', $student->user_id)->update($userTableFields);
                
                Toast::success('You have successfully updated: ' . $request->input('firstname') . ' ' . $request->input('lastname') . '.');

                return redirect()->route('platform.student.list');
            
            }else{
                //duplicate email found
                Toast::error('Email already exists.');
            }

        }catch(Exception $e){

            Alert::error('There was an error editing this student. Error Code: ' . $e->getMessage());
        }
    } 

    public function delete(Student $student)
    {
        try{
            $student->delete();
            User::where('id', $student->user_id)->delete();

            Toast::info('You have successfully deleted the student.');

            return redirect()->route('platform.student.list');

        }catch(Exception $e){
            
            Alert::error('There was an error deleting this student. Error Code: ' . $e->getMessage());
        }
    }

    //check for duplicate emails
    private function validEmail($request, $student){
        return count(User::whereNot('id', $student->user_id)->where('email', $request->input('email'))->get()) == 0;
    }

    //this functions returns the values that need to be inserted in the localadmin table in the db
    private function getStudentFields($request){
        $school_query = School::where('school_name', $request->input('school'))
            ->where('state_province', $request->input('state_province'))
            ->where('country', $request->input('country'));

        if ($request->input('country') == 'USA') {
            $school_query = $school_query->where('county', $request->input('county'));
        } else {
            $school_query = $school_query->where('city_municipality', $request->input('city_municipality'));
        }
        $school = $school_query->first();

        if(is_null($school)){
            throw New Exception('You are trying to enter a invalid school');
        }

        $studentTableFields = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'grade' => $request->input('grade'),
            'phonenumber' => $request->input('phonenumber'),
            'school' => $request->input('school'),
            'school_id' =>$school->id,
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
            'country' => $request->input('country'),
            'phonenumber' => $request->input('phonenumber'),
        ];
        
        return $userTableFields;
    }
}

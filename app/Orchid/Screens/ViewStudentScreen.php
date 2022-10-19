<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Student;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use App\Orchid\Layouts\ViewStudentLayout;

class ViewStudentScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'students' => Student::paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Students List';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Button::make('Delete Selected Students')
                ->icon('trash')
                ->method('deleteStudents'),
                
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
            ViewStudentLayout::class
        ];
    }

    public function deleteStudents(Request $request)
    {   
        //get all students from post request
        $students = $request->get('students');
        
        try{

            //if the array is not empty
            if(!empty($students)){

                //loop through the students and delete them from db
                foreach($students as $student){
                    Student::where('id', $student)->delete();
                }

                Alert::success('Selected students deleted succesfully');

            }else{
                Alert::warning('Please select students in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected students. Error Message: ' . $e);
        }
    }
}

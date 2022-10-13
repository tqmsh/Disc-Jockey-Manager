<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\School;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use App\Orchid\Layouts\ViewSchoolLayout;

class ViewSchoolScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'schools' => School::paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'School List';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Add School')
                ->icon('plus')
                ->route('platform.school.create'),

            Button::make('Delete Selected Schools')
                ->icon('trash')
                ->method('deleteSchools')
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
            ViewSchoolLayout::class
        ];
    }

    public function deleteSchools(Request $request)
    {   
        //get all schools from post request
        $schools = $request-> get('schools');
        
        try{

            //if the array is not empty
            if(!empty($schools)){

                //loop through the schools and delete them from db
                foreach($schools as $school){
                    School::where('id', $school)->delete();
                }

                Alert::success('Selected schools deleted succesfully');

            }else{
                Alert::warning('Please select schools in order to delete');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected schools. Error Message: ' . $e);
        }
    }
}

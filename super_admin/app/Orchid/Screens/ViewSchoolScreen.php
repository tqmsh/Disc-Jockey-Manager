<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\Student;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Illuminate\Http\JsonResponse;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewSchoolLayout;

class ViewSchoolScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Request $request): iterable
    {
        return [
            'schools' => School::latest('schools.created_at')->filter(request(['country', 'state_province', 'school', 'county']))->paginate(10)
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
            Link::make('Add New Schools')
                ->icon('plus')
                ->route('platform.school.create'),

            Button::make('Delete Selected Schools')
                ->icon('trash')
                ->method('deleteSchools')
                ->confirm(__('Are you sure you want to delete the selected schools?')),

                
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.school.list')
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
                    
                    Select::make('school')
                        ->title('School')
                        ->empty('No selection')
                        ->fromModel(School::class, 'school_name', 'school_name'),

                    Select::make('country')
                        ->title('Country')
                        ->empty('No selection')
                        ->fromModel(User::class, 'country', 'country'),
                    
                    Select::make('state_province')
                        ->title('State/Province')
                        ->empty('No selection')
                        ->fromModel(School::class, 'state_province', 'state_province'),
                        
                    Select::make('county')
                        ->title('County')
                        ->empty('No selection')
                        ->fromModel(School::class, 'county', 'county'),

                ]),
                
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewSchoolLayout::class
        ];
    }
    
    public function filter(Request $request){
        return redirect('/admin/schools?' 
                    .'&school=' . $request->get('school')
                    .'&county=' . $request->get('county')
                    .'&country=' . $request->get('country')
                    .'&state_province=' . $request->get('state_province'));
    }

    public function deleteSchools(Request $request)
    {   
        //get all schools from post request
        $schools = $request->get('schools');
        
        try{

            //if the array is not empty
            if(!empty($schools)){

                //loop through the schools and delete them from db
                foreach($schools as $school){
                    School::where('id', $school)->delete();
                }

                Toast::success('Selected schools deleted succesfully');

            }else{
                Toast::warning('Please select schools in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected schools. Error Message: ' . $e);
        }
    }
}
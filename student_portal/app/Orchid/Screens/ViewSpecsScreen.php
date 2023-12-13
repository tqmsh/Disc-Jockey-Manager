<?php

namespace App\Orchid\Screens;

use Exception;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Alert;
use App\Models\StudentSpecs;
use Illuminate\Support\Facades\Auth;

class ViewSpecsScreen extends Screen
{
    
    public string $description = 'Disclaimer: This information will be public to vendors';

    public $specs;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'specs' => StudentSpecs::where([['student_user_id', Auth::user()->id]])->get(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'My Specs';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
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
                Select::make('gender')
                    ->title('Gender')
                    ->required()
                    ->empty($this->specs[0]->gender)
                    ->value($this->specs[0]->gender)
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                        'Other' => 'Other',
                        'Prefer Not to Say' => 'Prefer Not to Say',
                    ]),

                Input::make('age')
                    ->title('Age')
                    ->type('number')
                    ->required()
                    ->placeholder($this->specs[0]->age)
                    ->value($this->specs[0]->age),    

                Select::make('hair_colour')
                    ->title('Hair Colour')
                    ->required()
                    ->empty($this->specs[0]->hair_colour)
                    ->value($this->specs[0]->hair_colour)
                    ->options([
                        'Brown' => 'Brown',
                        'Golden Brown' => 'Golden Brown',
                        'Blonde' => 'Blonde',
                        'Black' => 'Black',
                    ])->allowAdd(),

                Select::make('hair_style')
                    ->title('Hair Style')
                    ->required()
                    ->empty($this->specs[0]->hair_style)
                    ->value($this->specs[0]->hair_style)
                    ->options([
                        'Crew cut' => 'Crew cut',
                        'Buzz' => 'Buzz cut',
                        'Low' => 'Low fade',
                        'Mohawk' => 'Mohawk',
                    ])->allowAdd(),

                Select::make('complexion')
                    ->title('Complexion')
                    ->required()
                    ->empty($this->specs[0]->complexion)
                    ->value($this->specs[0]->complexion)
                    ->options([
                        'Very Fair' => 'Very Fair',
                        'Fair Skin' => 'Fair Skin',
                        'Medium' => 'Medium',
                        'Light Brown' => 'Light Brown',
                        'Brown' => 'Brown',
                        'Black' => 'Black',
                        'Other' => 'Other',
                    ]),

                Input::make('bust')
                    ->title('Bust')
                    ->type('number')
                    ->placeholder($this->specs[0]->bust)
                    ->value($this->specs[0]->bust) 
                    ->help('Enter in cm'),    

                Input::make('waist')
                    ->title('Waist')
                    ->type('number')
                    ->placeholder($this->specs[0]->waist)
                    ->value($this->specs[0]->waist) 
                    ->help('Enter in cm'),   
                
                Input::make('hips')
                    ->title('Hips')
                    ->type('number')
                    ->placeholder($this->specs[0]->hips)
                    ->value($this->specs[0]->hips) 
                    ->help('Enter in cm'),   

                Input::make('height')
                    ->title('Height')
                    ->type('number')
                    ->placeholder($this->specs[0]->height)
                    ->value($this->specs[0]->height) 
                    ->help('Enter in cm'),  

                Input::make('weight')
                    ->title('Weight')
                    ->type('number')
                    ->placeholder($this->specs[0]->weight)
                    ->value($this->specs[0]->weight) 
                    ->help('Enter in pounds'),  

                Button::make('Update Specs')
                    ->icon('check')
                    ->method('updateSpecs')
                    ->type(Color::PRIMARY()),
                
            ])->title('Specs'),
        ];
    }

    public function updateSpecs(Request $request)
    {
        try{

            //if the table id is not empty
            if(!empty($request)){
                
                //get the table from the db
                $student = StudentSpecs::where([['student_user_id', Auth::user()->id]])->get();
                $student[0]->gender = $request->get('gender');
                $student[0]->age = $request->get('age');
                $student[0]->hair_colour = $request->get('hair_colour');
                $student[0]->hair_style = $request->get('hair_style');
                $student[0]->complexion = $request->get('complexion');
                $student[0]->bust = $request->get('bust');
                $student[0]->waist = $request->get('waist');
                $student[0]->hips = $request->get('hips');
                $student[0]->height = $request->get('height');
                $student[0]->weight = $request->get('weight');
                    
                //save the table
                $student[0]->save();

                Toast::success('Specs updated succesfully');


            }else{
                Toast::warning('Please fill out all sections properly');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to edit your specs. Error Message: ' . $e->getMessage());
        }
    }
}

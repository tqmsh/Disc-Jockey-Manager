<?php

namespace App\Orchid\Screens;

use Exception;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use App\Models\Specs;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;




use App\Models\User;
use App\Models\School;
use App\Models\Student;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;


class EditSpecsScreen extends Screen
{
    public $specs;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Specs $specs): iterable
    {
        return [
            'specs' => $specs
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Specs';
    }

    public function description(): ?string
    {
        return"All information provided for specs are public to vendors";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.studentSpecs.list')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
       
                Layout::rows([

                    Input::make('age')
                        ->title('Age')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->age),

                    Select::make('gender')
                        ->title('Gender')
                        ->value($this->specs->gender)
                        ->horizontal()
                        ->empty("No Selection")
                        ->allowAdd()
                        ->options([
                            'female' => 'Female',
                            'male' => 'Male',
                            'other' => 'other',
                        ]),

                    Input::make('height')
                        ->title('Height (cm)')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->height),
                    
                    Input::make('weight')
                        ->title('Weight (pounds)')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->weight),
                    
                    Select::make('hair_colour')
                        ->title('Hair Color')
                        ->type('text')
                        ->allowAdd()
                        ->horizontal()
                        ->value($this->specs->hair_colour)
                        ->empty("No Selection")
                        ->options([
                            'black' => 'Black',
                            'brown' => 'Brown',
                            'blonde' => 'Blonde',
                            'red' => 'Red',
                            'gray' => 'Gray',
                            'white' => 'White',
                            'brunette' => 'Brunette',
                        ]),

                    Select::make('hair_style')
                        ->title('Hair Style')
                        ->type('text')
                        ->horizontal()
                        ->allowAdd()
                        ->value($this->specs->hair_style)
                        ->empty("No Selection")
                        ->options([
                            'straight' => 'Straight',
                            'wavy' => 'Wavy',
                            'curly' => 'Curly',
                            'pixie' => 'Pixie',
                            'layered' => 'Layered',
                            'braided' => 'Braided',
                            'ponytail' => 'Ponytail',
                            'updo' => 'Updo',
                        ]),

                    Select::make('complexion')
                        ->title('Skin Complexion')
                        ->type('text')
                        ->allowAdd()
                        ->horizontal()
                        ->value($this->specs->complexion)
                        ->empty("No Selection")
                        ->options([
                            'fair' => 'Fair',
                            'light' => 'Light',
                            'medium' => 'Medium',
                            'olive' => 'Olive',
                            'dark' => 'Dark',
                        ]),
                        
                    Select::make('eye_colour')
                        ->title('Eye Colour')
                        ->type('text')
                        ->allowAdd()
                        ->horizontal()                        
                        ->value($this->specs->eye_colour)
                        ->empty("No Selection")
                        ->options([
                            'amber' => 'Amber',
                            'blue' => 'Blue',
                            'brown' => 'Brown',
                            'gray' => 'Gray',
                            'green' => 'Green',
                            'hazel' => 'Hazel',
                        ]),
                        
                    Select::make('lip_style')
                        ->title('Lip Style')
                        ->type('text')
                        ->allowAdd()
                        ->horizontal()
                        ->value($this->specs->lip_style)
                        ->empty("No Selection")
                        ->options([
                            'full' => 'Full',
                            'thin' => 'Thin',
                            'plump' => 'Plump',
                            'cupids-bow' => "Cupid's Bow",
                            'straight-across' => 'Straight Across',
                            'rounded' => 'Rounded',
                            'heart-shaped' => 'Heart-shaped',
                        ]),
                        
                    Input::make('bust')
                        ->title('Bust (cm)')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->bust),
                        
                    Input::make('waist')
                        ->title('Waist (cm)')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->waist),
                        
                    Input::make('hips')
                        ->title('Hips (cm)')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->hips),
                        
                    TextArea::make('notes')
                        ->title('Notes')
                        ->type('text')
                        ->horizontal()
                        ->rows(5)
                        ->value($this->specs->notes),

                    Select::make('body_type')
                        ->title('Body Type')
                        ->allowAdd()
                        ->type('text')
                        ->horizontal()
                        ->empty("No Selection")
                        ->value($this->specs->body_type)
                        ->options([
                            'slim' => 'Slim',
                            'athletic' => 'Athletic',
                            'muscular' => 'Muscular',                                'average' => 'Average',
                                'stocky' => 'Stocky',
                            ]),

                    Button::make('Submit')
                        ->icon('check')
                        ->method('update'),

                    

                ]),
            ];
    }
    

    public function update(Specs $specs, Request $request)
    {
        try{

            // $school_id = School::where('school_name', $request->input('school'))
            //                     ->where('county', $request->input('county'))
            //                     ->where('state_province', $request->input('state_province'))
            //                     ->get('id')->value('id');

            // if(is_null($school_id)){
            //     throw New Exception('You are trying to enter a invalid school');
            // }

            $eventsFields = $request->all();
            // $eventsFields['school_id'] = $school_id;

            $specs->update($eventsFields);

            Toast::success('Specs Updated Succesfully');

            return redirect()->route('platform.studentSpecs.list');

        }catch(Exception $e){

            Alert::error('There was an error editing this event. Error Code: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Exception;
use App\Models\Specs;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Group;
use Illuminate\Support\Arr;



use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;


class CreateSpecsScreen extends Screen
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
        return 'Add Specs';
    }
    public function description(): ?string
    {
        return"All information provided for specs are public to vendors";
    }

    /**
     * Button commands.
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
                        ->horizontal()
                        ->allowAdd()
                        ->options([
                            'female' => 'Female',
                            'male' => 'Male',
                            'other' => 'other',
                        ]),
                    Input::make('age')
                        ->title('Age')
                        ->type('text')
                        ->horizontal(),

                    Input::make('height')
                        ->title('Height (cm)')
                        ->type('text')
                        ->horizontal(),


                    Input::make('weight')
                        ->title('Weight (Pounds)')
                        ->type('text')
                        ->horizontal(),

                    Select::make('hair_colour')
                        ->title('Hair Color')
                        ->type('text')
                        ->horizontal()
                        ->allowAdd()
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
                        ->horizontal()
                        ->allowAdd()
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
                        ->options([
                            'full' => 'Full',
                            'thin' => 'Thin',
                            'plump' => 'Plump',
                            'cupids-bow' => "Cupid's Bow",
                            'straight-across' => 'Straight Across',
                            'rounded' => 'Rounded',
                            'heart-shaped' => 'Heart-shaped',
                        ]),
                    

                    Group::make([

                        Select::make('bust')
                            ->help('Bust (cm)')
                            ->allowAdd()
                            ->options([
                                '32' => '32',
                                '33' => '33',
                                '34' => '34',
                                '35' => '35',
                                '36' => '36',
                                '37' => '37',
                                '38' => '38',
                                '39' => '39',
                                '40' => '40',
                                '41' => '41',
                                '42' => '42',
                                '43' => '43',
                                '44' => '44',
                                '45' => '45',
                                '46' => '46',
                                '47' => '47',
                                '48' => '48',
                                '49' => '49',
                            ])->title('Body‎')
                            ->placeholder('Filter by title'),

                            

                        Select::make('waist')
                            ->title('‎')
                            ->help('Waist (cm)')
                            ->allowAdd()
                            ->options(['24' => '24',
                                '25' => '25',
                                '26' => '26',
                                '27' => '27',
                                '28' => '28',
                                '29' => '29',
                                '30' => '30',
                                '31' => '31',
                                '32' => '32',
                                '33' => '33',
                                '34' => '34',
                                '35' => '35',
                                '36' => '36',
                                '37' => '37',
                                '38' => '38',
                                '39' => '39',
                                '40' => '40',
                                '41' => '41',
                                '42' => '42',
                                '43' => '43',
                                '44' => '44',
                                '45' => '45',
                                '46' => '46',
                        ]),

                        Select::make('hips')
                            ->title('‎')
                            ->help('Hips (cm)')
                            ->allowAdd()
                            ->options([
                                '32' => '32',
                                '33' => '33',
                                '34' => '34',
                                '35' => '35',
                                '36' => '36',
                                '37' => '37',
                                '38' => '38',
                                '39' => '39',
                                '40' => '40',
                                '41' => '41',
                                '42' => '42',
                                '43' => '43',
                                '44' => '44',
                                '45' => '45',
                                '46' => '46',
                                '47' => '47',
                                '48' => '48',
                                '49' => '49',
                                '50' => '50',
                                '51' => '51',
                                '52' => '52',
                            ]),
                        ])->fullWidth(),

                    Select::make('body_type')
                        ->title('Body Type')
                        ->allowAdd()
                        ->type('text')
                        ->horizontal()
                        ->options([
                            'slim' => 'Slim',
                            'athletic' => 'Athletic',
                            'muscular' => 'Muscular',
                            'average' => 'Average',
                            'stocky' => 'Stocky',
                        ]),

                    TextArea::make('notes')
                        ->title('Notes')
                        ->type('text')
                        ->horizontal()
                        ->rows(5),

                    Button::make('Submit')
                        ->icon('check')
                        ->method('createSpecs')

                
                
            
        ]),
    ];
}


    public function createSpecs(Request $request){
        // Use parameters from Button!!!
        $gender = $request->get('gender');

        try{
            $formFields = $request->all();
            $formFields['student_user_id'] = Auth()->id();
            $formFields = Arr::except($formFields, ['_token']);
            // $formFields['gender'] = $gender;
            // dd($formFields);

            // if ($gender == 1) {
            //     // $formFields['height'] = ($request->get('male_height_feet') . "'" . $request->get('male_height_inches') . "\"");
            //     $formFields['height'] = ($request->get('male_height'));
            //     $formFields['weight'] = $request->get('male_weight_pounds');
            //     $formFields['complexion'] = $request->get('male_skin_complexion');
            //     $formFields['notes'] = $request->get('male_notes');
            // } else {
            //     // $formFields['height'] = ($request->get('height_feet') . "'" . $request->get('height_inches') . "\"");
            //     $formFields['height'] = ($request->get('height'));
            //     $formFields['weight'] = $request->get('weight');
            //     $formFields['complexion'] = $request->get('complexion');
            //     $formFields['notes'] = $request->get('notes');
            // }
            // dd($formFields);

            Specs::updateOrCreate($formFields);

            Toast::success('Specs Added Succesfully');
            
            return redirect()->route('platform.studentSpecs.list');

        }catch(Exception $e){
            
            Alert::error('There was an error creating this event. Error Code: ' . $e->getMessage());
        }
    }
}
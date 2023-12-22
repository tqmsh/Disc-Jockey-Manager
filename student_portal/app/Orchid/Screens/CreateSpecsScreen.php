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
        Layout::tabs([
            'Female' => [
                Layout::rows([
                    // Group::make([
                    //     Select::make('height_feet')
                    //         ->vertical()
                    //         ->options([
                    //             '4' => '4',
                    //             '5' => '5',
                    //             '6' => '6'
                    //         ])
                    //         ->title('Height')
                    //         ->help('Feet'),
                    //     Select::make('male_height_inches')
                    //     ->vertical()
                    //     ->options([
                    //         '0' => '0',
                    //         '1' => '1',
                    //         '2' => '2',
                    //         '3' => '3',
                    //         '4' => '4',
                    //         '5' => '5',
                    //         '6' => '6',
                    //         '7' => '7',
                    //         '8' => '8',
                    //         '9' => '9',
                    //         '10' => '10',
                    //         '11' => '11',
                    //     ])
                    //     ->help('Inches')->title('‎ '),
                    // ])->autoWidth(),
                    
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

                    // Select::make('hair_length')
                    //     ->title('Hair Length')
                    //     ->type('text')
                    //     ->horizontal()
                    //     ->options([
                    //         'short' => 'Short',
                    //         'medium' => 'Medium',
                    //         'long' => 'Long',
                            
                    //     ]),

                    Select::make('complexion')
                        ->title('Skin Complexion')
                        ->type('text')
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
                        ->help('Bust')
                        ->options([
                            '32A' => '32A',
                            '32B' => '32B',
                            '32C' => '32C',
                            '34A' => '34A',
                            '34B' => '34B',
                            '34C' => '34C',
                            '36A' => '36A',
                            '36B' => '36B',
                            '36C' => '36C',
                            '38A' => '38A',
                            '38B' => '38B',
                            '38C' => '38C',
                            '40A' => '40A',
                            '40B' => '40B',
                            '40C' => '40C',
                            '42A' => '42A',
                            '42B' => '42B',
                            '42C' => '42C',
                        ])->title('Body‎')
                        ->placeholder('Filter by title'),

                        

                    Select::make('waist')
                        ->title('‎')
                        ->help('Waist (Inches)')
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
                        ->help('Hips (Inches)')
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

                    TextArea::make('notes')
                        ->title('Notes')
                        ->type('text')
                        ->horizontal()
                        ->rows(5),

                    Button::make('Submit')
                        ->icon('check')
                        ->method('createSpecs')
                        // Add Parameters with Buttons!!!
                        ->parameters(['gender' => '2'])
                ]),
            ],
            'Male' => [
                Layout::rows([
                    // Group::make([
                        //     Select::make('male_height_feet')
                        //         ->options([
                        //             '4' => '4',
                        //             '5' => '5',
                        //             '6' => '6'
                        //         ])
                        //         ->title('Height')
                        //         ->help('Feet'),
                        //     Select::make('male_height_inches')
                        //     ->options([
                        //         '0' => '0',
                        //         '1' => '1',
                        //         '2' => '2',
                        //         '3' => '3',
                        //         '4' => '4',
                        //         '5' => '5',
                        //         '6' => '6',
                        //         '7' => '7',
                        //         '8' => '8',
                        //         '9' => '9',
                        //         '10' => '10',
                        //         '11' => '11',
                        //     ])
                        //     ->help('Inches')->title('‎ '),
                        // ])->autoWidth(),

                    Input::make('male_height')
                        ->title('Height (cm)')
                        ->type('text')
                        ->horizontal(),


                    Input::make('male_weight_pounds')
                        ->title('Weight (Pounds)')
                        ->type('text')
                        ->horizontal(),

                    Select::make('body_type')
                        ->title('Body Type')
                        ->type('text')
                        ->horizontal()
                        ->options([
                            'slim' => 'Slim',
                            'athletic' => 'Athletic',
                            'muscular' => 'Muscular',
                            'average' => 'Average',
                            'stocky' => 'Stocky',
                        ]),
                    
                    Select::make('male_skin_complexion')
                        ->title('Skin Complection')
                        ->type('text')
                        ->horizontal()
                        ->options([
                            'fair' => 'Fair',
                            'light' => 'Light',
                            'medium' => 'Medium',
                            'olive' => 'Olive',
                            'dark' => 'Dark',
                        ]),
                    
                    TextArea::make('male_notes')
                        ->title('Notes')
                        ->type('text')
                        ->horizontal()
                        ->rows(5),
                    
                    Button::make('Submit')
                        ->icon('check')
                        ->method('createSpecs')
                        // Add Parameters with Buttons!!!
                        ->parameters(['gender' => '1'])
                ]),
            ],
        ]),
    ];
}


    public function createSpecs(Request $request){
        // Use parameters from Button!!!
        $gender = $request->get('gender');

        try{
            $formFields = $request->all();
            $formFields['student_user_id'] = auth()->id();
            $formFields['gender'] = $gender;
            // dd($formFields);

            if ($gender == 1) {
                // $formFields['height'] = ($request->get('male_height_feet') . "'" . $request->get('male_height_inches') . "\"");
                $formFields['height'] = ($request->get('male_height'));
                $formFields['weight'] = $request->get('male_weight_pounds');
                $formFields['complexion'] = $request->get('male_skin_complexion');
                $formFields['notes'] = $request->get('male_notes');
            } else {
                // $formFields['height'] = ($request->get('height_feet') . "'" . $request->get('height_inches') . "\"");
                $formFields['height'] = ($request->get('height'));
                $formFields['weight'] = $request->get('weight');
                $formFields['complexion'] = $request->get('complexion');
                $formFields['notes'] = $request->get('notes');
            }

            Specs::create($formFields);

            Toast::success('Specs Added Succesfully');
            
            return redirect()->route('platform.studentSpecs.list');

        }catch(Exception $e){
            
            Alert::error('There was an error creating this event. Error Code: ' . $e->getMessage());
        }
    }
}

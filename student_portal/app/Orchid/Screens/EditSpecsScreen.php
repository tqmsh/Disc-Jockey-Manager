<?php

namespace App\Orchid\Screens;

use Exception;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use App\Models\Specs;
use Orchid\Screen\Fields\Input;


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
        if ($this->specs->gender == 2) {
            return [
                Layout::rows([

                    Input::make('height')
                        ->title('Height')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->height),
                    
                    Input::make('weight')
                        ->title('Weight')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->weight_pounds),
                    
                    Input::make('hair_color')
                        ->title('Hair Color')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->hair_color),
                        
                    Input::make('hair_style')
                        ->title('Hair Style')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->hair_style),
                        
                    Input::make('hair_length')
                        ->title('Hair Length')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->hair_length),
                        
                    Input::make('skin_complexion')
                        ->title('Skin Complection')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->skin_complexion),
                        
                    Input::make('eye_color')
                        ->title('Eye Color')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->eye_color),
                        
                    Input::make('lip_style')
                        ->title('Lip Style')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->lip_style),
                        
                    Input::make('bust')
                        ->title('Bust')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->bust),
                        
                    Input::make('waist')
                        ->title('Waist')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->waist),
                        
                    Input::make('hips')
                        ->title('Hips')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->hips),
                        
                    Input::make('notes')
                        ->title('Notes')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->notes),


                    Button::make('Submit')
                        ->icon('check')
                        ->method('update'),


                ]),
            ];
        } else {
            // If gender is not equal to 2, return an empty array or any alternative layout/content you want.
            return [
                Layout::rows([

                    Input::make('height')
                        ->title('Height')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->height),
                    
                    Input::make('weight_pounds')
                        ->title('Weight')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->weight_pounds),
                    
                    Input::make('body_type')
                        ->title('Body Type')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->body_type),
                    
                    Input::make('skin_complexion')
                        ->title('Skin Complection')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->skin_complexion),
                    
                    Input::make('notes')
                        ->title('Notes')
                        ->type('text')
                        ->horizontal()
                        ->value($this->specs->notes),
                    
                    
                    Button::make('Submit')
                        ->icon('check')
                        ->method('update'),
                ]),
            ];
        }
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

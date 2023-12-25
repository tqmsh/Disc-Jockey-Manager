<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use App\Models\Specs;
use Orchid\Screen\Sight;


use Orchid\Screen\Actions\Link;
use Illuminate\Support\Facades\Auth;


use App\Orchid\Layouts\ViewFemaleSpecsLayout;
use App\Orchid\Layouts\ViewMaleSpecsLayout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;





class ViewSpecsScreen extends Screen
{
    public $specs;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Specs $specs): iterable
    {
        $user_specs = Specs::where('student_user_id', Auth::user()->id)->first();

        return [
            // dd($specs),
            'user_specs' => $user_specs,
        ];
    }

    // public function __construct(Specs $specs)
    // {
    //     $this->specs = $specs;
    // }

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
        $user_specs = Specs::where('student_user_id', Auth::user()->id)->first();

        $commands = [];

        // Add Specs link if user hasn't added specs
        if ($user_specs === null) {
            $commands[] = Link::make('Add Specs')
                ->icon('plus')
                ->route('platform.specs.create');
        }

        // Edit Specs link if user has added specs
        if ($user_specs !== null) {
            $commands[] = Link::make('Edit Specs')
                ->icon('pencil')
                ->route('platform.specs.edit', Auth::user()->id);
        }

        return $commands;
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $this->specs = 'user_specs';

        $user_specs = Specs::where('student_user_id', Auth::user()->id)->first();

        if ($user_specs !== null) {
            if ($user_specs->gender == 2) {
                return [
                    Layout::legend($this->specs, [
                        Sight::make('height', 'Height')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->height ?? " ";
                        }),    

                        Sight::make('age', 'Age')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->age ?? " ";
                            }),
                        
                        Sight::make('weight', 'Weight')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->weight ?? " "; 
                        }),   

                        Sight::make('hair_colour', 'Hair Color')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->hair_colour ?? " ";
                            
                        }), 

                        Sight::make('hair_style', 'Hair Style')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->hair_style ?? " ";
                            
                        }), 

                        // Sight::make('hair_length', 'Hair Length')
                        //     ->render(function (Specs $specs_1 = null) {
                        //         return $specs_1->hair_length ?? " ";
                            
                        // }), 

                        Sight::make('complexion', 'Skin complexion')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->complexion ?? " ";
                            
                        }), 

                        Sight::make('eye_colour', 'Eye Color')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->eye_colour ?? " ";
                            
                        }), 

                        Sight::make('lip_style', 'Lip Style')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->lip_style ?? " ";
                            
                        }), 

                        Sight::make('bust', 'Bust')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->bust ?? " ";
                            
                        }), 

                        Sight::make('waist', 'Waist')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->waist ?? " ";
                            
                        }), 

                        Sight::make('hips', 'Hips')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->hips ?? " ";
                            
                        }), 

                        Sight::make('notes', 'Notes')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->notes ?? " ";
                            
                        }), 
                    ]),
                ];
            } else {
                return [
                    Layout::legend($this->specs, [
                        Sight::make('height', 'Height')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->height ?? " ";
                        }),    

                        Sight::make('age', 'Age')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->age ?? " ";
                            }),

                        Sight::make('weight_pounds', 'Weight')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->weight ?? " ";
                        }),   

                        Sight::make('body_type', 'Body Type')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->body_type ?? " ";
                        }), 
                        
                        Sight::make('skin_complexion', 'Skin Complexion')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->complexion ?? " ";
                        }), 

                        Sight::make('notes', 'Notes')
                            ->render(function (Specs $specs_1 = null) {
                                return $specs_1->notes ?? " ";
                        }),
                    ]),
                ];
            }    
        } else {
            // If user hasn't added specs, return an empty array (no layout will be displayed)
            return [];
        }
    }
}
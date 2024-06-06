<?php

namespace App\Orchid\Screens;

use App\Models\TourElement;
use App\Models\TourScreen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CreateTourElementScreen extends Screen
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
        return 'Create an Element For A Tour';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create Tour Element')
                ->icon('plus')
                ->method('createTourEl'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.tour-element.list')

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

                Select::make('screen')
                    ->title('Screen')
                    ->placeholder('Select the Screen for the Element')
                    ->options(TourScreen::pluck('screen', 'id'))
                    ->horizontal()
                    ->required()
                    ->empty('Start typing to search...')
                    ->required(),

                Input::make('title')
                    ->title('Title of Popup Element')
                    ->placeholder('Enter the title of the popup element')
                    ->horizontal()
                    ->required(),

                Input::make('element')
                    ->title('HTML Element')
                    ->placeholder('Enter the tag/class/query selector of the element')
                    ->horizontal()
                    ->required(),

                Input::make('description')
                    ->title('Description')
                    ->placeholder('Enter the description of the pop-up box.')
                    ->horizontal()
                    ->required(),

                Input::make('order_element')
                    ->title('Order of the elements')
                    ->type('number')
                    ->placeholder('Enter the order of the elements')
                    ->horizontal()
                    ->required(),


                ])

            ];
    }

    public function createTourEl(Request $request){

        try{
            $fields = $request->validate([
                'screen' => 'required',
                'title' => 'required',
                'element' => 'required',
                'description' => 'required',
                'order_element' => 'required',
            ]);

            $tourEl = TourElement::create($fields);

            $tourEl->save();

            Toast::success('Element created successfully!');
            return redirect()->route('platform.tour-element.list');


        }catch(Exception $e){
            Toast::error('There was an error creating the element. Error code: ' . $e->getMessage());
        }
    }

}

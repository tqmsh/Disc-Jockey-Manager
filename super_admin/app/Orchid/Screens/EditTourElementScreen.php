<?php

namespace App\Orchid\Screens;

use App\Models\TourElement;
use App\Models\TourScreen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditTourElementScreen extends Screen
{
    public $tourElement;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(TourElement $tourElement): iterable
    {
        abort_if(Auth::user()->role != 1, 403, 'You are not authorized to view this page.');

        return [
            'tourElement' => $tourElement
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit: ' . $this->tourElement->title ;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Update Tour Element')
                ->icon('plus')
                ->method('updateTourEl'),

            Button::make('Delete Tour Element')
                ->icon('trash')
                ->method('deleteTourEl'),

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
                    ->required()
                    ->value($this->tourElement->screen),

                Input::make('title')
                    ->title('Title of Popup Element')
                    ->placeholder('Enter the title of the popup element')
                    ->horizontal()
                    ->required()
                    ->value($this->tourElement->title),

                Input::make('element')
                    ->title('HTML Element')
                    ->placeholder('Enter the tag/class/query selector of the element')
                    ->horizontal()
                    ->required()
                    ->value($this->tourElement->element),

                Input::make('description')
                    ->title('Description')
                    ->placeholder('Enter the description of the pop-up box.')
                    ->horizontal()
                    ->required()
                    ->value($this->tourElement->description),

                Input::make('order_element')
                    ->title('Order of the elements')
                    ->type('number')
                    ->placeholder('Enter the order of the elements')
                    ->horizontal()
                    ->required()
                    ->value($this->tourElement->order_element),


            ])
        ];
    }


    public function updateTourEl(Request $request, TourElement $tourElement){

        $fields = $request->validate([
            'screen' => 'required',
            'title' => 'required',
            'element' => 'required',
            'description' => 'nullable',
            'order_element' => 'required',
        ]);

        try{
            $tourElement->update($fields);
            Toast::success('Tour Element Updated');
            return redirect()->route('platform.tour-element.list');
        } catch(Exception $e){
            Toast::error('Error Updating Tour Element. Error Code: ' . $e->getMessage());
        }
    }

    public function deleteTourEl(TourElement $tourElement){
        try{
            $tourElement->delete();
            Toast::success('Tour Element Deleted');
            return redirect()->route('platform.tour-element.list');
        } catch(Exception $e){
            Toast::error('Error Deleting Tour Element. Error Code: ' . $e->getMessage());
        }
    }
}

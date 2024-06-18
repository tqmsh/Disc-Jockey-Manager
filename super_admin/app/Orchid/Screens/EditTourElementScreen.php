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
use PHPUnit\Util\Exception;

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

                Input::make('screen')
                    ->title('Screen URI')
                    ->placeholder('Enter the Screen for the Element')
                    ->horizontal()
                    ->required()
                    ->value($this->tourElement->screen),

                Select::make('portal')
                    ->title('Portal')
                    ->empty('What portal should this be in?')
                    ->required()
                    ->horizontal()
                    ->options([
                        0 => 'Local Admin',
                        1 => 'Student',
                        2 => 'Vendor'
                    ])
                    ->value($this->tourElement->portal),


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

                Input::make('extra')
                    ->title('Route If Needed')
                    ->placeholder('Enter the route uri if needed for element id')
                    ->horizontal()
                    ->value($this->tourElement->extra),


            ])
        ];
    }
    private function validOrderOfEl($tourElement, $order, $portal, $screen){
        return count(TourElement::whereNot('id', $tourElement->id)->where('order_element', $order)->where('portal', $portal)->where('screen', $screen)->get()) == 0;
    }



    public function updateTourEl(Request $request, TourElement $tourElement){

        $fields = $request->validate([

            'screen' => 'required',
            'portal'=>'required',
            'title' => 'required',
            'element' => 'required',
            'description' => 'required',
            'order_element' => 'required',
            'extra' => 'nullable',

        ]);

        try{

            if(!($this->validOrderOfEl($tourElement, $fields['order_element'], $fields['portal'], $fields['screen']))){
                throw New Exception('Order Number Already Exists For This Portal and Screen. ');
                //return;

            }else {
                $tourElement->update($fields);
                Toast::success('Tour Element Updated');
                return redirect()->route('platform.tour-element.list');
            }
        } catch(Exception $e){
            Toast::error('Error Updating Tour Element. Error Code: ' . $e->getMessage());
            return back()->withInput();

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

<?php

namespace App\Orchid\Screens;

use App\Models\Food;
use App\Models\Events;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Cropper;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;

class CreateFoodScreen extends Screen
{
    public $event;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        return [
            'event' => $event,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Add Food Item to: ' . $this->event->event_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Add')
                ->icon('plus')
                ->method('createItem'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.eventFood.list', $this->event->id),
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

            Layout::columns([

                Layout::rows([

                    Cropper::make("item.image")
                        ->storage("s3")
                        ->title("Image")
                        ->help("Image to display")
                        ->acceptedFiles('image/jpeg,image/png,image/jpg'),

                    Input::make('item.name')
                        ->title('Name')
                        ->placeholder('Ex. Pizza')
                        ->required()
                        ->help('Enter the name of your item item.')
                        ->horizontal(),

                    TextArea::make('item.description')
                        ->title('Description')
                        ->placeholder('Ex. Pepperoni Pizza with extra cheese, onions, olives and mushrooms.')
                        ->help('Enter the description of your item item.')
                        ->rows(4)
                        ->horizontal(),
                ])->title('Item Details'),


                Layout::rows([


                    CheckBox::make('item.nut_free')
                        ->title('Nut Free')
                        ->help('Check if this item is nut free.')
                        ->sendTrueOrFalse(),
                        
                    CheckBox::make('item.vegetarian')
                        ->title('Vegetarian')
                        ->help('Check if this item is vegetarian.')
                        ->sendTrueOrFalse(),
                    
                    CheckBox::make('item.vegan')
                        ->title('Vegan')
                        ->help('Check if this item is vegan.')
                        ->sendTrueOrFalse(),

                    CheckBox::make('item.halal')
                        ->title('Halal')
                        ->help('Check if this item is halal.')
                        ->sendTrueOrFalse(),
                    
                    CheckBox::make('item.gluten_free')
                        ->title('Gluten Free')
                        ->help('Check if this item is gluten free.')
                        ->sendTrueOrFalse(),

                    CheckBox::make('item.kosher')
                        ->title('Kosher')
                        ->help('Check if this item is kosher.')
                        ->sendTrueOrFalse(),

                    CheckBox::make('item.dairy_free')
                        ->title('Dairy Free')
                        ->help('Check if this item is dairy free.')
                        ->sendTrueOrFalse(),

                ])->title('Dietary Restrictions'),

            ]),

        ];
    }

    public function createItem(Events $event, Request $request){

        try{
            $validate = request('item');
            unset($validate['image']);
            $item = request('item');
            $item['event_id'] = $event->id;

            Food::firstOrCreate($validate, $item);

            Toast::success('Item added successfully.');

            return redirect()->route('platform.eventFood.list', $event->id);
        }catch(\Exception $e){
            Toast::error('Error: ' . $e->getMessage());
        }
    }
}

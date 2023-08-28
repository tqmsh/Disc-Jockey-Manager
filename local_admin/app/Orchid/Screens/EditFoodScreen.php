<?php

namespace App\Orchid\Screens;

use App\Models\Food;
use App\Models\Events;
use Orchid\Screen\Screen;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditFoodScreen extends Screen
{
    public $event;
    public $food;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event, Food $food): iterable
    {
        return [
            'event' => $event,
            'food' => $food,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Item: ' . $this->food->name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('save')
                ->method('updateItem'),

            Button::make('Delete')
                ->icon('trash')
                ->method('deleteItem')
                ->confirm('Are you sure you want to delete this item?'),
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
                        ->acceptedFiles('image/jpeg,image/png,image/jpg')
                        ->value($this->food->image),

                    Input::make('item.name')
                        ->title('Name')
                        ->placeholder('Ex. Pizza')
                        ->required()
                        ->help('Enter the name of your item item.')
                        ->horizontal()
                        ->value($this->food->name),

                    TextArea::make('item.description')
                        ->title('Description')
                        ->placeholder('Ex. Pepperoni Pizza with extra cheese, onions, olives and mushrooms.')
                        ->help('Enter the description of your item item.')
                        ->rows(4)
                        ->horizontal()
                        ->value($this->food->description),
                ])->title('Item Details'),


                Layout::rows([


                    CheckBox::make('item.nut_free')
                        ->placeholder('Nut Free')
                        ->sendTrueOrFalse()
                        ->value($this->food->nut_free),
                        
                    CheckBox::make('item.vegetarian')
                        ->placeholder('Vegetarian')
                        ->sendTrueOrFalse()
                        ->value($this->food->vegetarian),
                    
                    CheckBox::make('item.vegan')
                        ->placeholder('Vegan')
                        ->sendTrueOrFalse()
                        ->value($this->food->vegan),

                    CheckBox::make('item.halal')
                        ->placeholder('Halal')
                        ->sendTrueOrFalse()
                        ->value($this->food->halal),
                    
                    CheckBox::make('item.gluten_free')
                        ->placeholder('Gluten Free')
                        ->sendTrueOrFalse()
                        ->value($this->food->gluten_free),

                    CheckBox::make('item.kosher')
                        ->placeholder('Kosher')
                        ->sendTrueOrFalse()
                        ->value($this->food->kosher),

                    CheckBox::make('item.dairy_free')
                        ->placeholder('Dairy Free')
                        ->sendTrueOrFalse()
                        ->value($this->food->dairy_free),

                ])->title('Dietary Restrictions'),

            ]),
        ];
    }

    public function updateItem(Events $event, Food $food)
    {
        try{
            unset($food['image']);
            $item = request('item');
            $item['event_id'] = $event->id;

            Food::updateOrCreate($food->toArray(), $item);

            Toast::success('Item updated successfully.');

            return redirect()->route('platform.eventFood.list', $event->id);
        }catch(\Exception $e){
            Toast::error('Error: ' . $e->getMessage());
        }
    }

    public function deleteItem(Events $event, Food $food)
    {
        try{
            $food->delete();

            Toast::success('Item deleted successfully.');

            return redirect()->route('platform.eventFood.list', $event->id);
        }catch(\Exception $e){
            Toast::error('Error: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewEventFoodLayout;
use Orchid\Screen\Fields\CheckBox;

class ViewEventFoodScreen extends Screen
{
    public $event;
    public $allergies;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event, Request $request): iterable
    {
        $filters = $request->get('filter') ?? [];
        return [
            'event' => $event,
            'allergies' => $event->allergies(),
            'food' => $event->food()->filter($filters)->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Menu for: ' . $this->event->event_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Add Item')
                ->icon('plus')
                ->route('platform.eventFood.create', $this->event->id),

            Button::make('Delete Selected Items')
                ->icon('trash')
                ->method('deleteItems')
                ->confirm('Are you sure you want to delete these items?'),
            
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list'),
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
                    Input::make('filter.name')
                        ->title('Name')
                        ->value(request()->get('filter')['name'] ?? '')
                        ->placeholder('Filter by name'),

                    Group::make([
                        Button::make('Filter')
                            ->method('applyFilters')
                            ->icon('filter'),

                        Button::make('Clear Filters')
                            ->method('clearFilters')
                            ->icon('close'),
                    ]),

                ]),

                
                Layout::rows([

                    CheckBox::make('filter.nut_free')
                        ->placeholder('Nut Free')
                        ->value(request()->get('filter')['nut_free'] ?? false),

                    CheckBox::make('filter.vegetarian')
                        ->placeholder('Vegetarian')
                        ->value(request()->get('filter')['vegetarian'] ?? false),

                    CheckBox::make('filter.vegan')
                        ->placeholder('Vegan')
                        ->value(request()->get('filter')['vegan'] ?? false),

                    CheckBox::make('filter.halal')
                        ->placeholder('Halal')
                        ->value(request()->get('filter')['halal'] ?? false),
                    
                    CheckBox::make('filter.dairy_free')
                        ->placeholder('Dairy Free')
                        ->value(request()->get('filter')['dairy_free'] ?? false),
                                            
                    CheckBox::make('filter.kosher')
                        ->placeholder('Kosher')
                        ->value(request()->get('filter')['kosher'] ?? false),
                    
                    CheckBox::make('filter.gluten_free')
                        ->placeholder('Gluten Free')
                        ->value(request()->get('filter')['gluten_free'] ?? false),
                ]),

                Layout::view('allergies'),
            ]),


            ViewEventFoodLayout::class,
        ];
    }

    public function applyFilters(Request $request, Events $event)
    {
        $filterParams = $request->input('filter');
        return redirect()->route('platform.eventFood.list', ['event_id' => $event->id, 'filter' => $filterParams]);
    }

    public function clearFilters(Events $event)
    {
        return redirect()->route('platform.eventFood.list', ['event_id' => $event->id]);
    }

    public function deleteItems(Events $event)
    {
        try {
            $ids = request('items');

            Events::findOrFail($event->id)->food()->whereIn('id', $ids)->delete();

            Toast::success('Items Deleted Successfully!');
        } catch (\Exception $e) {
            Toast::error($e->getMessage());
        }
    }

    public function redirect(Events $event, $food_id, $type)
    {
        if (request('type') == "edit") {
            return redirect()->route('platform.eventFood.edit', ['event_id' => $event->id, 'food_id' => request('food_id')]);
        }
    }
}

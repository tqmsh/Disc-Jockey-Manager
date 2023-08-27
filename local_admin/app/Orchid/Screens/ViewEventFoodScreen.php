<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;

class ViewEventFoodScreen extends Screen
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
            'food' => $event->food()->paginate(10),
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
                ->route('platform.event.list', $this->event->id),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [];
    }

    public function deleteItems()
    {
        try {
            $ids = $this->getRequest()->get('ids');

            Events::findOrFail($this->event->id)->food()->whereIn('id', $ids)->delete();

            Toast::success('Items Deleted Successfully!');
        } catch (\Exception $e) {
            Toast::error($e->getMessage());
        }
    }
}

<?php

namespace App\Orchid\Screens;

use App\Models\User;
use App\Models\Events;
use App\Models\EventBids;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use App\Models\Vendors;
use App\Notifications\GeneralNotification;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewEventBidsLayout;
use App\Orchid\Layouts\ViewPendingEventBidsLayout;

class ViewEventBidScreen extends Screen
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
            'pendingBids' => EventBids::filter(request(['category_id']))->where('event_id', $event->id)->where('status', 0)->latest()->paginate(10),
            'previousBids' => EventBids::filter(request(['category_id']))->where('event_id', $event->id)->whereNot('status', 0)->orderBy('status')->latest()->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Bids on: ' . $this->event->event_name;
    }

    public function description(): ?string
    {
        return 'Interested Vendor Categories: ' . ($this->event->getInterestedCategoriesNames() ?? 'None');
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
                ->route('platform.event.list')
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
                Group::make([
                    
                    Select::make('category_id')
                        ->help('Type in box to search')
                        ->empty('Filter Category')
                        ->fromModel(Categories::query(), 'name'),

                    Button::make('Filter')
                        ->icon('filter')
                        ->method('filter')
                        ->type(Color::DEFAULT()),
                ]),
                
            ]),

            Layout::tabs([
                'Pending Bids' => [
                    ViewPendingEventBidsLayout::class
                ],
                'Previous Bids' => [
                    ViewEventBidsLayout::class
                ],
            ]),
        ];
    }
    
    public function filter(Events $event)
    {
        return redirect()->route('platform.eventBids.list', [$event->id, 'category_id' => request('category_id')]);
    }

    public function redirect($bid){
        return redirect()-> route('platform.eventBids.edit', $bid);
    }

    public function updateBid(Events $event)
    {
        try{
            
            $bid = EventBids::find(request('bid_id'));
            $bid->status = request('choice');
            $bid->save();
            $vendor = Vendors::where('user_id', $bid->user_id)->first();
            $vendor_user = User::find($bid->user_id);

            $adPrice = 50;

            if($bid->status == 1){

                $vendor->decrement('credits', $adPrice);

                $vendor_user->notify(new GeneralNotification([
                    'title' => 'Event Bid Accepted',
                    'message' => 'Your bid for ' . $event->event_name . ' has been accepted!',
                    'action' => '/admin/bid/history'
                ]));
                
            } else {
                $vendor_user->notify(new GeneralNotification([
                    'title' => 'Event Bid Rejected',
                    'message' => 'Your bid for ' . $event->event_name . ' has been rejected!',
                    'action' => '/admin/bid/history'
                ]));
            }

            Toast::success('Bid updated successfully!');
            return redirect()->route('platform.eventBids.list', $event);
        } catch (\Exception $e) {
            Toast::error('Something went wrong. Error: ' . $e->getMessage());
        }
    }
}

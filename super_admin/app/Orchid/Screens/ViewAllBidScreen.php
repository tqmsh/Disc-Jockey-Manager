<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use App\Models\EventBids;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use App\Models\StudentBids;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewEventBidsLayout;
use App\Orchid\Layouts\ViewPendingEventBidsLayout;
use App\Orchid\Layouts\ViewPendingStudentBidsLayout;
use App\Orchid\Layouts\ViewStudentBidsLayout;
use Orchid\Support\Facades\Toast;
use App\Models\Vendors;


class ViewAllBidScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'pendingBids' => EventBids::filter(request(['category_id']))->where('status', 0)->latest()->paginate(request()->query('pagesize', 10)),
            'previousBids' => EventBids::filter(request(['category_id']))->whereNot('status', 0)->orderBy('status')->latest()->paginate(request()->query('pagesize', 10)),
            'pendingStudentBids' => StudentBids::filter(request(['category_id']))->where('status', 0)->latest()->paginate(request()->query('pagesize', 10)),
            'previousStudentBids' => StudentBids::filter(request(['category_id']))->whereNot('status', 0)->orderBy('status')->latest()->paginate(request()->query('pagesize', 10))
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'All Bids'; 
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
                ->route('platform.bid.list')
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
                'Pending Event Bids' => [
                    ViewPendingEventBidsLayout::class
                ],
                
                'Pending Student Bids' => [
                    ViewPendingStudentBidsLayout::class
                ],
                'Previous Event Bids' => [
                    ViewEventBidsLayout::class
                ],
                'Previous Student Bids' => [
                    ViewStudentBidsLayout::class
                ],
            ]),
        ];
    }
    
    public function filter(Events $event)
    {
        return redirect()->route('platform.bid.list', [$event->id, 'category_id' => request('category_id')]);
    }

    public function redirect($bid, $bid_type){
        if ($bid_type == 'event') {
            return redirect()-> route('platform.eventBid.edit', $bid);
        } else {
            return redirect()-> route('platform.studentBid.edit', $bid);
        }
    }
    
    public function updateBid()
    {
        $bid_type = request('bid_type');
        if ($bid_type == 'student') {
            $bid = StudentBids::find(request('bid_id'));
        } else if ($bid_type == 'event') {
            $bid = EventBids::find(request('bid_id'));
        }
        $bid->status = request('choice');
        $bid->save();

        $adPrice = 50;
        $vendor = Vendors::where('user_id', $bid->user_id)->first();

        if (($bid->status) == 1) {
            $vendor->decrement('credits', $adPrice);
        } 

        Toast::success('Bid updated successfully!');

        return redirect()->route('platform.bid.list');
    }
}

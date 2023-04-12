<?php

namespace App\Orchid\Screens;

use App\Models\Region;
use App\Models\EventBids;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\StudentBids;
use Illuminate\Support\Arr;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\EventBidLayout;
use App\Orchid\Layouts\StudentBidLayout;

class ViewBidHistoryScreen extends Screen
{
    public $paidRegionIds;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        //convert all the users paid regions to a array
        $array = Auth::user()->paidRegions->toArray();

        //get all the region_ids of the array
        $this->paidRegionIds =  Arr::pluck($array, ['region_id']);
         
        return [
            'eventBids' => EventBids::filter(request(['region_id']))->where('user_id', Auth::user()->id)->orderBy('status')->paginate(10),
            'studentBids' => StudentBids::where('user_id', Auth::user()->id)->orderBy('status')->paginate(10),

            'metrics' => [
                'bidsAccepted'    => ['value' => number_format(count(EventBids::where('user_id', Auth::user()->id)->where('status', 1)->get()))],
                'bidsRejected' => ['value' =>  number_format(count(EventBids::where('user_id', Auth::user()->id)->where('status', 2)->get()))],
                'bidsPending' => ['value' => number_format(count(EventBids::where('user_id', Auth::user()->id)->where('status', 0)->get()))],
                'total'    =>  number_format(count(EventBids::where('user_id', Auth::user()->id)->get())),
            ],
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Bid History';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [

            Layout::metrics([
                'Total Bids' => 'metrics.total',
                'Pending Bids' => 'metrics.bidsPending',
                'Total Bids Accepted'    => 'metrics.bidsAccepted',
                'Total Bids Rejected' => 'metrics.bidsRejected',
            ]),

            Layout::rows([

                Group::make([
                    
                    Select::make('region_id')
                        ->empty('Filter by region')
                        ->fromModel(Region::query()->whereIn('id', $this->paidRegionIds), 'name'),

                    Button::make('Filter')
                        ->icon('filter')
                        ->method('filter')
                        ->type(Color::DEFAULT()),
                ]),
                
            ]),

            Layout::tabs([
                'Event Bids' => EventBidLayout::class,
                'Student Bids' => StudentBidLayout::class,
            ]),
        ];
    }

    public function editBid($bidId, $type){
        if($type == 'event'){
            return redirect()->route('platform.eventBid.edit', $bidId);
        } else {
            return redirect()->route('platform.studentBid.edit', $bidId);
        }
    }

    public function filter(){
        return redirect()->route('platform.bidhistory.list', request(['region_id']));
    }
}

<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\Region;
use App\Models\EventBids;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewPastEventBidLayout;
use App\Orchid\Layouts\ViewActiveEventBidLayout;

class ViewEventBidScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'activeBids' => EventBids::latest()->where('status', 0)->filter(request(['region_id']))->paginate(10),
            'pastBids' => EventBids::latest()->whereNot('status', 0)->filter(request(['region_id']))->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'View Vendor Event Bids';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            
            Link::make('Create a Event Bid')
                ->icon('plus')
                ->route('platform.bid.create'),

            Button::make('Delete Selected Bids')
                ->icon('trash')
                ->method('deleteBids')
                ->confirm(__('Are you sure you want to delete the selected bids?')),

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

                    Select::make('region_id')
                        ->title('Region')
                        ->empty('No Selection')
                        ->fromModel(Region::query(), 'name')
                ]),
                
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            Layout::tabs([
                'Active Bids' => [
                    ViewActiveEventBidLayout::class
                ],
                'Previous Bids' => [
                    ViewPastEventBidLayout::class
                ],
            ]),

        ];
    }

    public function deleteBids()
    {
        try{
            $bids = request()->input('bids');
            if (count($bids) > 0) {
                foreach ($bids as $bid) {
                    EventBids::find($bid)->delete();
                }

                Toast::success('Selected bids deleted successfully.');
            }else{
                Toast::error('Please select at least one bid to delete.');
            } 
        } catch(Exception $e) {
            Alert::error('There was an error deleting the selected bids' . $e->getMessage());
        }
    }

    public function filter(Request $request){
        return redirect('/admin/bids?' 
                    .'&region_id=' . $request->get('region_id'));
    }
    
    public function updateBid()
    {
        $bid = EventBids::find(request('bid_id'));
        $bid->status = request('choice');
        $bid->save();
        Toast::success("Bid " . ($bid->status == 1) ? 'Accepted' : 'Rejected');
        return redirect()->route('platform.bid.list');
    }
}

<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Events;
use App\Models\Vendors;
use Orchid\Screen\Sight;
use App\Models\EventBids;
use Orchid\Screen\Screen;
use App\Models\Categories;
use App\Models\Localadmin;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Notifications\GeneralNotification;

class CreateEventBidScreen extends Screen
{
    public $event;
    public $vendor; 

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        $this->vendor = Vendors::where('user_id', Auth::user()->id)->first();
        
        return [
            'event' => $event
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Create Bid For: ' . $this->event->event_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Button::make('Send Bid (50 credits)')
                ->icon('plus')
                ->method('createBid'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.bidopportunities.list')
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
            Layout::legend('event',[
                Sight::make('event_start_time', 'Event Date'),
                Sight::make('school', 'School Name'),
                Sight::make('venue_id', 'Venue')->render(function (){

                    $venue = Vendors::find($this->event->venue_id);

                    return $venue === null ? '<i class="text-danger">●</i> Undecided' : $venue->company_name;
                }),

                Sight::make('company_name', 'Your Company Name')->render(function(){

                    return $this->vendor->company_name;
                }),

                Sight::make('category_id', 'Your Category')->render(function(){

                    $catName = Categories::find($this->vendor->category_id)->name;

                    return $catName;
                }),

                Sight::make('website', 'Your Website')->render(function(){

                    return Link::make($this->vendor->website)->href($this->vendor->website);
                }),
            ])->title('Bid Information'),

            Layout::rows([

                Select::make('package_id')
                    ->title('Package')
                    ->options(function (){

                        $packages = Auth::user()->packages;

                        $options = [];

                        foreach ($packages as $package){
                            $options[$package->id] = $package->package_name;
                        }

                        return $options;
                    })
                    ->required()
                    ->empty('Start typing to search...')
                    ->help('Select the package you would like to bid on for this event.'), 

                TextArea::make('notes')
                    ->title('Bid Notes')
                    ->placeholder('Enter your bid notes')
                    ->rows(5)
                    ->help('Enter any notes you would like to include with your bid. This is optional.'),

                TextArea::make('contact_instructions')
                    ->title('Contact Instructions')
                    ->placeholder('Enter your contact instructions')
                    ->rows(5)
                    ->help('Enter any instructions you would like.')

            ])->title('Your Bid')
        ];
    }

    public function createBid(Events $event){

        $vendor = Vendors::where('user_id', Auth::id())->first();
        $localAdmins = User::where('role', 2)->whereIn('id', Localadmin::where('school_id', $event->school_id)->get('user_id')->toArray())->get();

            try{  

                if ((Auth::user()->vendor->credits) >= 50) {
                    if($this->validBid($event)){
                        
                        EventBids::create([
                            'user_id' => $vendor->user_id,
                            'event_id' => $event->id,
                            'package_id' => request('package_id'),
                            'notes' => request('notes'),
                            'category_id' => $vendor->category_id,
                            'event_date' => $event->event_start_time,
                            'school_name' => $event->school,
                            'region_id' => $event->region_id,
                            'company_name' => $vendor->company_name,
                            'url' => $vendor->website,
                            'contact_instructions' => request('contact_instructions'),
                            'status' => 0
                        ]);

                        foreach($localAdmins as $admin){
                            $admin->notify(new GeneralNotification([
                                'title' => 'A Bid Has Been Placed On Your Event',
                                'message' => 'A vendor has created a bid on your event: ' . $event->event_name . '. Click to view the bid.',
                                'action' => '/admin/events/bids/' . $event->id,
                            ]));
                        }
                            
                        Toast::success('Bid created succesfully');
                            
                        return redirect()->route('platform.bidopportunities.list');
                    }else{
                        Toast::error('Bid already exists');
                    }
            } else {
                Toast::error('Insuficient Credits');
                return redirect()->route('platform.shop');
            }
    
        }catch(Exception $e){
            Alert::error('Error: ' . $e->getMessage());
        }
    }

    private function validBid(Events $event){

        return count(EventBids::where('user_id', Auth::user()->id)
                             ->where('event_id', $event->id)
                             ->where('package_id', request('package_id'))
                             ->get()) == 0;
    }
}

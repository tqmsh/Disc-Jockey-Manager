<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use App\Models\Vendors;
use Orchid\Screen\Sight;
use App\Models\VendorBids;
use Orchid\Screen\Screen;
use App\Models\Categories;
use Exception;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Facades\Alert;

class CreateBidScreen extends Screen
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

            Button::make('Send Bid')
                ->icon('plus')
                ->method('createBid'),

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

        $vendor = Vendors::where('user_id', Auth::user()->id)->first();

        try{   

            VendorBids::create([
                'user_id' => $vendor->user_id,
                'event_id' => $event->id,
                'package_id' => request('package_id'),
                'notes' => request('notes'),
                'category_id' => $vendor->category_id,
                'event_date' => $event->event_start_time,
                'school_name' => $event->school,
                'company_name' => $vendor->company_name,
                'url' => $vendor->website,
                'contact_instructions' => request('contact_instructions'),
                'status' => 'pending'
            ]);
    
            Toast::success('Bid created succesfully');
    
            return redirect()->route('platform.event.list');

        }catch(Exception $e){
            Alert::error('Error: ' . $e);
        }
    }
}

<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Region;
use App\Models\Vendors;
use Orchid\Screen\Sight;
use App\Models\BeautyGroup;
use Orchid\Screen\Screen;
use App\Models\Categories;
use App\Models\BeautyGroupBid;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Notifications\GeneralNotification;

class CreateBeautyGroupBidScreen extends Screen
{
    public $beautyGroup;
    public $vendor;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(BeautyGroup $beautyGroup): iterable
    {
        $this->vendor = Vendors::where('user_id', Auth::user()->id)->first();

        return [
            'beautyGroup' => $beautyGroup
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Create Bid For: ' . $this->beautyGroup->name;
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
            Layout::legend('beautyGroup',[
                Sight::make('creator_user_id', 'Beauty Group Owner Email')->render(function(){

                    return $this->beautyGroup->owner->email;
                }),
                Sight::make('name', 'Beauty group Name'),
                Sight::make('pickup_location', 'Pickup Location'),
                Sight::make('dropoff_location', 'Dropoff Location'),
                Sight::make('depart_time', 'Depart Time'),
                Sight::make('dropoff_time', 'Dropoff Time'),
                Sight::make('notes', 'Notes'),
                Sight::make('region', 'Region')->render(function(){

                    return Region::find($this->beautyGroup->school->region_id)->name;
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

    public function createBid(BeautyGroup $beautyGroup){

        $vendor = Vendors::where('user_id', Auth::user()->id)->first();

            try{   

                if ((Auth::user()->vendor->credits) >= 50) {

                    if($this->validBid($beautyGroup)){
                        
                        BeautyGroupBid::create([
                            'user_id' => $vendor->user_id,
                            'beauty_group_id' => $beautyGroup->id,
                            'package_id' => request('package_id'),
                            'notes' => request('notes'),
                            'category_id' => $vendor->category_id,
                            'school_name' => $beautyGroup->school->school_name,
                            'region_id' => $beautyGroup->school->region_id,
                            'company_name' => $vendor->company_name,
                            'url' => $vendor->website,
                            'contact_instructions' => request('contact_instructions'),
                            'status' => 0
                        ]);

                        $beautyGroupOwner = User::find($beautyGroup->owner->user_id);

                        $beautyGroupOwner->notify(new GeneralNotification([
                            'title' => 'New Beauty Group Bid',
                            'message' => 'You have a new bid for your beauty group: ' . $beautyGroup->name,
                            'action' => '/admin/beauty-groups',
                        ]));
                            
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

    private function validBid(BeautyGroup $beautyGroup){

        return count(BeautyGroupBid::where('user_id', Auth::id())
                             ->where('beauty_group_id', $beautyGroup->id)
                             ->where('package_id', request('package_id'))
                             ->get()) == 0;
    }
}

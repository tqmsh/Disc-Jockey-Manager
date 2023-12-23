<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Region;
use App\Models\Vendors;
use Orchid\Screen\Sight;
use App\Models\LimoGroup;
use Orchid\Screen\Screen;
use App\Models\Categories;
use App\Models\LimoGroupBid;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Notifications\GeneralNotification;

class CreateLimoGroupBidScreen extends Screen
{
    public $limoGroup;
    public $vendor;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(LimoGroup $limoGroup): iterable
    {
        $this->vendor = Vendors::where('user_id', Auth::user()->id)->first();

        return [
            'limoGroup' => $limoGroup
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Create Bid For: ' . $this->limoGroup->name;
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
            Layout::legend('limoGroup',[
                Sight::make('creator_user_id', 'Limo Group Owner Email')->render(function(){

                    return $this->limoGroup->owner->email;
                }),
                Sight::make('name', 'Limo group Name'),
                Sight::make('pickup_location', 'Pickup Location'),
                Sight::make('dropoff_location', 'Dropoff Location'),
                Sight::make('depart_time', 'Depart Time'),
                Sight::make('dropoff_time', 'Dropoff Time'),
                Sight::make('notes', 'Notes'),
                Sight::make('region', 'Region')->render(function(){

                    return Region::find($this->limoGroup->school->region_id)->name;
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

    public function createBid(LimoGroup $limoGroup){

        $vendor = Vendors::where('user_id', Auth::user()->id)->first();

            try{   

                if ((Auth::user()->vendor->credits) >= 50) {

                    if($this->validBid($limoGroup)){
                        
                        LimoGroupBid::create([
                            'user_id' => $vendor->user_id,
                            'limo_group_id' => $limoGroup->id,
                            'package_id' => request('package_id'),
                            'notes' => request('notes'),
                            'category_id' => $vendor->category_id,
                            'school_name' => $limoGroup->school->school_name,
                            'region_id' => $limoGroup->school->region_id,
                            'company_name' => $vendor->company_name,
                            'url' => $vendor->website,
                            'contact_instructions' => request('contact_instructions'),
                            'status' => 0
                        ]);

                        $limoGroupOwner = User::find($limoGroup->owner->user_id);

                        $limoGroupOwner->notify(new GeneralNotification([
                            'title' => 'New Limo Group Bid',
                            'message' => 'You have a new bid for your limo group: ' . $limoGroup->name,
                            'action' => '/admin/limo-groups',
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

    private function validBid(LimoGroup $limoGroup){

        return count(LimoGroupBid::where('user_id', Auth::id())
                             ->where('limo_group_id', $limoGroup->id)
                             ->where('package_id', request('package_id'))
                             ->get()) == 0;
    }
}

<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\BeautyGroupBid;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;

class EditBeautyBidScreen extends Screen
{
    public $beautyBid;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(BeautyGroupBid $beautyBid): iterable
    {
        abort_if($beautyBid->user_id != Auth::id(), 403, 'You are not authorized to view this page.');
        return [
            'beautyBid' => $beautyBid
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Beauty Bid: ' . $this->beautyBid->beautyGroup->name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Update Bid')
                ->icon('plus')
                ->method('updateBid'),

            Button::make('Delete Bid')
                ->icon('trash')
                ->method('delete'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.bidhistory.list')            
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
                    ->help('Select the package you would like to bid for this student.')
                    ->value($this->beautyBid->package_id), 

                TextArea::make('notes')
                    ->title('Bid Notes')
                    ->placeholder('Enter your bid notes')
                    ->rows(5)
                    ->help('Enter any notes you would like to include with your bid. This is optional.')
                    ->value($this->beautyBid->notes), 

                TextArea::make('contact_instructions')
                    ->title('Contact Instructions')
                    ->placeholder('Enter your contact instructions')
                    ->rows(5)
                    ->help('Enter any instructions you would like.')
                    ->value($this->beautyBid->contact_instructions), 


            ])->title('Your Bid')
        ];
    }

   public function updateBid(BeautyGroupBid $beautyBid, Request $request){

        try{
            
            if($this->validBid($beautyBid, $request, $beautyBid->id)){

                $beautyBid = $beautyBid->fill($request->all())->save();

                if($beautyBid){
                    Toast::success('Bid updated successfully!');
                    return redirect()->route('platform.bidhistory.list');
                }else{
                    Toast::error('Error: Bid not updated for an unknown reason.');
                }
            }else{
                Toast::error('Bid already exists.');
            }

        } catch (\Exception $e) {
            Toast::error('Error: ' . $e->getMessage());
        }
    }

    public function delete(BeautyGroupBid $beautyBid)
    {
        try{
            
            $beautyBid->delete();

            Toast::success('You have successfully deleted the bid.');
    
            return redirect()->route('platform.bidhistory.list');

        }catch(\Exception $e){

            Toast::error('There was an error deleting this bid. Error Code: ' . $e->getMessage());
        }
    }

    private function validBid(BeautyGroupBid $beautyBid, Request $request, $currentBidId){

        return count(BeautyGroupBid::where('user_id', Auth::user()->id)
                             ->where('beauty_group_id', $beautyBid->beauty_group_id)
                             ->where('status', 0)
                             ->where('package_id', $request->package_id)
                             ->whereNot('id', $currentBidId)
                             ->get()) == 0;
    }
}

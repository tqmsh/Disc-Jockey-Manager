<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\EventBids;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;

class EditBidScreen extends Screen
{
    public $eventBid;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(EventBids $eventBid): iterable
    {
        return [
            'eventBid' => $eventBid
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Bid';
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
                    ->help('Select the package you would like to bid on for this event.')
                    ->value($this->eventBid->package_id), 

                TextArea::make('notes')
                    ->title('Bid Notes')
                    ->placeholder('Enter your bid notes')
                    ->rows(5)
                    ->help('Enter any notes you would like to include with your bid. This is optional.')
                    ->value($this->eventBid->notes), 

                TextArea::make('contact_instructions')
                    ->title('Contact Instructions')
                    ->placeholder('Enter your contact instructions')
                    ->rows(5)
                    ->help('Enter any instructions you would like.')
                    ->value($this->eventBid->contact_instructions), 


            ])->title('Your Bid')
        ];
    }

   public function updateBid(EventBids $eventBid, Request $request){

        try{
            
            if($this->validBid($eventBid, $request, $eventBid->id)){

                $eventBid = $eventBid->fill($request->all())->save();

                if($eventBid){
                    Toast::success('Bid updated successfully!');
                    return redirect()->route('platform.bidhistory.list');
                }else{
                    Alert::error('Error: Bid not updated for an unknown reason.');
                }
            }else{
                Toast::error('Bid already exists.');
            }

        } catch (Exception $e) {
            Alert::error('Error: ' . $e->getMessage());
        }
    }

    public function delete(EventBids $eventBid)
    {
        try{
            
            $eventBid->delete();

            Toast::success('You have successfully deleted the bid.');
    
            return redirect()->route('platform.bidhistory.list');

        }catch(Exception $e){

            Alert::error('There was an error deleting this bid. Error Code: ' . $e);
        }
    }

    private function validBid(EventBids $eventBid, Request $request, $current_eventBid_id){

        return count(EventBids::where('user_id', Auth::user()->id)
                             ->where('event_id', $eventBid->event_id)
                             ->where('status', 0)
                             ->where('package_id', $request->package_id)
                             ->whereNot('id', $current_eventBid_id)
                             ->get()) == 0;
    }
}

<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\StudentBids;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use App\Models\VendorPackage;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;

class EditStudentBidScreen extends Screen
{
    public $studentBid;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(StudentBids $studentBid): iterable
    {
        return [
            'studentBid' => $studentBid
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Student Bid';
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

                Select::make('package_id')
                    ->title('Package')
                    ->options(function (){

                        $packages = VendorPackage::where('user_id', $this->studentBid->user_id)->get();

                        $options = [];

                        foreach ($packages as $package){
                            $options[$package->id] = $package->package_name;
                        }

                        return $options;
                    })
                    ->required()
                    ->empty('Start typing to search...')
                    ->help('Select the package you would like to bid on for this student.')
                    ->value($this->studentBid->package_id), 

                TextArea::make('notes')
                    ->title('Bid Notes')
                    ->placeholder('Enter your bid notes')
                    ->rows(5)
                    ->help('Enter any notes you would like to include with your bid. This is optional.')
                    ->value($this->studentBid->notes), 

                TextArea::make('contact_instructions')
                    ->title('Contact Instructions')
                    ->placeholder('Enter your contact instructions')
                    ->rows(5)
                    ->help('Enter any instructions you would like.')
                    ->value($this->studentBid->contact_instructions), 


            ])->title('Your Bid')
        ];
    }

   public function updateBid(StudentBids $studentBid, Request $request){

        try{
            
            if($this->validBid($studentBid, $request, $studentBid->id)){

                $studentBid = $studentBid->fill($request->all())->save();

                if($studentBid){
                    Toast::success('Bid updated successfully!');
                    return redirect()->route('platform.bid.list');
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

    public function delete(StudentBids $studentBid)
    {
        try{
            
            $studentBid->delete();

            Toast::success('You have successfully deleted the bid.');
    
            return redirect()->route('platform.bid.list');

        }catch(Exception $e){

            Alert::error('There was an error deleting this bid. Error Code: ' . $e->getMessage());
        }
    }

    private function validBid(StudentBids $studentBid, Request $request, $current_studentBid_id){

        return count(StudentBids::where('user_id', $studentBid->user_id)
                             ->where('student_user_id', $studentBid->student_id)
                             ->where('package_id', $request->package_id)
                             ->whereNot('id', $current_studentBid_id)
                             ->get()) == 0;
    }
}

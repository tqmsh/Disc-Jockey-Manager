<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Region;
use App\Models\Student;
use App\Models\Vendors;
use Orchid\Screen\Sight;
use Orchid\Screen\Screen;
use App\Models\Categories;
use App\Models\StudentBids;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Notifications\GeneralNotification;

class CreateStudentBidScreen extends Screen
{
    public $student;
    public $vendor;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Student $student): iterable
    {
        $this->vendor = Vendors::where('user_id', Auth::user()->id)->first();

        return [
            'student' => $student
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Create Bid For: ' . $this->student->firstname . ' ' . $this->student->lastname ;
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
            Layout::legend('student',[
                Sight::make('firstname', 'First Name'),
                Sight::make('lastname', 'Last Name'),
                Sight::make('email', 'Email'),
                Sight::make('region', 'Region')->render(function(){

                    return Region::find($this->student->school()->first()->region_id)->name;
                }),
                Sight::make('allergies', 'Allergies'),

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

    public function createBid(Student $student){

        $vendor = Vendors::where('user_id', Auth::user()->id)->first();

            try{   

                if($this->validBid($student)){
                    
                    StudentBids::create([
                        'user_id' => $vendor->user_id,
                        'student_user_id' => $student->user_id,
                        'package_id' => request('package_id'),
                        'notes' => request('notes'),
                        'category_id' => $vendor->category_id,
                        'school_name' => $student->school,
                        'region_id' => $student->school()->first()->region_id,
                        'company_name' => $vendor->company_name,
                        'url' => $vendor->website,
                        'contact_instructions' => request('contact_instructions'),
                        'status' => 0
                    ]);

                    $user = User::find($student->user_id);

                    $user->notify(new GeneralNotification([
                        'title' => 'New Bid placed on you!',
                        'message' => 'You have a new bid placed on you from: ' . $vendor->company_name,
                        'action' => '/admin/bids',
                    ]));
                        
                    Toast::success('Bid created succesfully');
                        
                    return redirect()->route('platform.bidopportunities.list');
                }else{
                    Toast::error('Bid already exists');
                }
    
            }catch(Exception $e){
                Alert::error('Error: ' . $e->getMessage());
            }
    }

    private function validBid(Student $student){

        return count(StudentBids::where('user_id', Auth::user()->id)
                             ->where('student_user_id', $student->user_id)
                             ->where('package_id', request('package_id'))
                             ->get()) == 0;
    }
}

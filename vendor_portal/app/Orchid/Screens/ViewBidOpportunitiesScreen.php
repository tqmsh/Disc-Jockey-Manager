<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\Region;
use App\Models\School;
use App\Models\Student;
use App\Models\Vendors;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use App\Models\Localadmin;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use App\Models\VendorPaidRegions;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewEventLayout;
use App\Orchid\Layouts\ViewStudentBidLayout;

class ViewBidOpportunitiesScreen extends Screen
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

        //get all events with the region_id matching an id in the array
        return [
            'events' => Events::filter(request(['region_id']))->whereIn('region_id', $this->paidRegionIds)->paginate(10),
            'students' => Student::whereIn('school_id', School::whereIn('region_id', $this->paidRegionIds)->pluck('id'))->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Bid Opportunities';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
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
                'Events' => ViewEventLayout::class,
                'Students' => ViewStudentBidLayout::class,
            ]),


            Layout::rows([

                Input::make('category_name')
                    ->title('Suggest Category Name')
                    ->placeholder('Enter the name of the category')
                    ->help('Suggest a category to be reviewed and approved by an admin'),
                    
                Button::make('Add')
                    ->icon('plus')
                    ->type(Color::DEFAULT())
                    ->method('createCategory'),
            ])
        ];
    }

    public function filter(){
        return redirect()->route('platform.event.list', request(['region_id']));
    }

    //this method will create the category
    public function createCategory()
    {
        //take category from request then check for duplicate
        $category = request('category_name');

        if(is_null($category)){
            
            Toast::error('Category name cannot be empty');

        }else if(!empty(Categories::where('name', $category)->first())){
            
            Toast::error('Category already exists');
            
        }else{

            //update the category if it already exists or create it if it doesnt
            $check = Categories::create(['name' => $category, 'status' => 0]);

            if($check){

                Toast::success('Category has been created for review!');

            }else{

                Toast::error('Category could not be created for an unknown reason');
            }
        }
    }

    

    public function redirect(){

        if(request('type') == 'event'){

            return redirect()->route('platform.bid.create', request('event_id'));

        } else if(request('type') == 'student'){

            return redirect()->route('platform.studentBid.create', request('student_id'));
        }
    }
}
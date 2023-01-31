<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
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

class ViewEventScreen extends Screen
{
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
        $paidRegionIds =  Arr::pluck($array, ['region_id']);

        //get all events with the region_id matching an id in the array
        return [
            'events' => Events::whereIn('region_id', $paidRegionIds)->paginate(10)
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
                    
                    Select::make('school')
                        ->title('School')
                        ->empty('No selection')
                        ->fromModel(Events::class, 'school', 'school'),

                    Select::make('country')
                        ->title('Country')
                        ->empty('No selection')
                        ->fromModel(School::class, 'country', 'country'),

                    Select::make('school_board')
                        ->title('School Board')
                        ->empty('No selection')
                        ->fromModel(School::class, 'school_board', 'school_board'),

                    Select::make('state_province')
                        ->title('State/Province')
                        ->empty('No selection')
                        ->fromModel(School::class, 'state_province', 'state_province'),
                ]),
                
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewEventLayout::class,

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

    public function filter(Request $request){
        return redirect('/admin/events?' 
                    .'&school=' . $request->get('school')
                    .'&country=' . $request->get('country')
                    .'&school_board=' . $request->get('school_board')
                    .'&state_province=' . $request->get('state_province'));
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

    public function redirect($event_id){
        return redirect()->route('platform.bid.create', $event_id);
    }
}

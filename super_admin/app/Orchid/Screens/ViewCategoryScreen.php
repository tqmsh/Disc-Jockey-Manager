<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewCategoryLayout;
use App\Orchid\Layouts\ViewPendingCategoryLayout;
use Exception;

class ViewCategoryScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'categories' => Categories::latest()->where('status', 1)->paginate(min(request()->query('pagesize', 10), 100)),
            'pending_categories' => Categories::latest()->where('status', 0)->paginate(min(request()->query('pagesize', 10), 100))
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Categories';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Link::make("Re-order Categories")
                ->icon('pencil')
                ->route("platform.category.order"),

            Button::make('Approve Selected')
                ->icon('check')
                ->method('approveCats')
                ->confirm(__('Are you sure you want to approve the selected categories?')),

            Button::make('Delete Selected')
                ->icon('trash')
                ->method('deleteCats')
                ->confirm(__('Are you sure you want to delete the selected categories?')),
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

                Input::make('category_name')
                ->title('Category Name')
                ->placeholder('Enter the name of the category'),

                Button::make('Add')
                ->icon('plus')
                ->type(Color::DEFAULT())
                ->method('createCategory'),
            ]),

            Layout::tabs([
                'Active Categories' => ViewCategoryLayout::class,
                'Pending Categories' => ViewPendingCategoryLayout::class,
            ]),
        ];
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

            // Create category and auto approve it
            // order_num counts from 1, with 0 reserved for invalid order value
            $num_categories = count(Categories::where('status', 1)->get()->toArray());
            $check = Categories::create(['name' => $category, "status" => 1, "order_num"=>$num_categories+1]);

            if($check){

                Toast::success('Category created successfully');

            }else{

                Toast::error('Category could not be created for an unknown reason');
            }
        }
    }

    public function approveCats(){

            //get all categories from post request
            $categories = request('pending_categories');

            try{
                //if the array is not empty
                if(!empty($categories)){

                    //loop through the categories and update them
                    foreach($categories as $category){
                        $num_categories = count(Categories::where('status', 1)->get()->toArray());
                        Categories::where('id', $category)->update(['status' => 1, "order_num"=>$num_categories+1]);
                    }

                    Toast::success('Selected categories approved succesfully');

                }else{
                    Toast::warning('Please select categories in order to approve them');
                }

            }catch(Exception $e){
                Toast::error('There was a error trying to approve the selected categories. Error Message: ' . $e->getMessage());
            }
    }


    public function redirect($category){
        return redirect()-> route('platform.category.edit', $category);
    }

    public function deleteCats(Request $request)
    {
        //get all categories from post request
        $categories = $request->get('categories');
        $pending_categories = $request->get("pending_categories");
        
        if(is_null($categories)){
            $categories = $pending_categories;
        }

        try{
            //if the array is not empty
            if(!empty($categories)){

                Categories::whereIn('id', $categories)->delete();

                Toast::success('Selected categories deleted succesfully');

            }else{
                Toast::warning('Please select categories in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected categories. Error Message: ' . $e->getMessage());
        }
    }
}

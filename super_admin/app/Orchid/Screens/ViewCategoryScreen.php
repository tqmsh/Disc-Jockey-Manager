<?php

namespace App\Orchid\Screens;

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
            'categories' => Categories::latest()->paginate(8),
            'pending_categories' => Categories::latest()->where('status', 0)->paginate(8)
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

            Button::make('Approve Selected Pending Categories')
                ->icon('check')
                ->method('approveCats')
                ->confirm(__('Are you sure you want to approve the selected categories?')),

            Button::make('Delete Selected Categories')
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
            
            ViewCategoryLayout::class,

            ViewPendingCategoryLayout::class,
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

            //update the category if it already exists or create it if it doesnt
            $check = Categories::create(['name' => $category]);

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
                        Categories::where('id', $category)->update(['status' => 1]);
                    }
    
                    Toast::success('Selected categories approved succesfully');
    
                }else{
                    Toast::warning('Please select categories in order to approve them');
                }
    
            }catch(Exception $e){
                Toast::error('There was a error trying to approve the selected categories. Error Message: ' . $e->getMessage());
            }
    }

    public function deleteCats(Request $request)
    {   
        //get all categories from post request
        $categories = $request->get('categories');
        
        try{
            //if the array is not empty
            if(!empty($categories)){

                //loop through the categories and delete them from db
                foreach($categories as $category){
                    Categories::where('id', $category)->delete();
                }

                Toast::success('Selected categories deleted succesfully');

            }else{
                Toast::warning('Please select categories in order to delete them');
            }

        }catch(Exception $e){
            Toast::error('There was a error trying to deleted the selected categories. Error Message: ' . $e->getMessage());
        }
    }
}

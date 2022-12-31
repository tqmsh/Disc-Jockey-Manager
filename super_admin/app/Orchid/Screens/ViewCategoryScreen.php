<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\Categories;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewCategoryLayout;

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
            'categories' => Categories::paginate(8),
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
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [

            ViewCategoryLayout::class,

            Layout::rows([

                Input::make('category_name')
                    ->title('Category Name')
                    ->placeholder('Enter the name of the category')
                    ->help('This is the name of the category that will be displayed to the user.'),
                    
                Button::make('Add')
                    ->icon('plus')
                    ->method('createCategory'),
            ])
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
}

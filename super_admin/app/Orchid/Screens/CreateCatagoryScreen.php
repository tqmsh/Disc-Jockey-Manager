<?php

namespace App\Orchid\Screens;

use App\Models\Categories;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CreateCatagoryScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Create a New Category';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Add')
                ->icon('plus')
                ->method('createCategory'),
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
                    ->placeholder('Enter the name of the category')
                    ->help('This is the name of the category that will be displayed to the user.')
                    ->required(),
            ])
        ];
    }

    //this method will create the category
    public function createCategory()
    {
        //take category from request then check for duplicate
        $category = request('category_name');

        if(!empty(Categories::where('name', $category)->first())){
            
            Toast::error('Category already exists');
            return redirect()->route('platform.category.create');
            
        }else{

            //update the category if it already exists or create it if it doesnt
            $check = Categories::create(['name' => $category]);

            if($check){

                Toast::success('Category created successfully');
                return redirect()->route('platform.category.create');

            }else{

                Toast::error('Category could not be created for an unknown reason');
                return redirect()->route('platform.category.create');
            }
        }
    }
}

<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\Categories;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;

class EditCategoryScreen extends Screen
{
    public $category;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Categories $category): iterable
    {
        return [
            'category' => $category
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit: ' . $this->category->name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Button::make('Submit')
                ->icon('check')
                ->method('update'),

            Button::make('Delete Category')
                ->icon('trash')
                ->method('delete'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.category.list')
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
                    ->value($this->category->name),
            ])
        ];
    }

    public function update(Request $request, Categories $category){

        try{
            $validated = $request->validate([
                'category_name' => Rule::unique('categories', 'name')->ignore($category->id)
            ]);
            $category->name = $validated['category_name'];
    
            $category->save();
    
            Toast::success('Category updated successfully.');
    
            return redirect()->route('platform.category.list');

        }catch(Exception $e){
            Alert::error($e->getMessage());
        }
    }

    public function delete(Categories $category)
    {
        try{
            
            $category->delete();

            Toast::info('You have successfully deleted the category.');
    
            return redirect()->route('platform.category.list');

        }catch(Exception $e){

            Alert::error('There was an error deleting this category. Error Code: ' . $e->getMessage());
        }
    }
}

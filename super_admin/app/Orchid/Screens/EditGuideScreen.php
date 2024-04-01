<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Guide;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;

class EditGuideScreen extends Screen
{
    public $guide;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Guide $guide): iterable
    {
        return [
            'guide' => $guide
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Guide: ' . $this->guide->guide_name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Update')
                ->icon('check')
                ->method('update'),

            Button::make('Delete Guide')
                ->icon('trash')
                ->method('delete')
                ->confirm('Are you sure you want to delete this guide?'),
            
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.guide.list'),
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
                Input::make('ordering')
                    ->title('Ordering')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->guide->ordering),
                    
                Input::make('guide_name')
                    ->title('Guide Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->guide->guide_name),

                Select::make('category')
                ->title('Category')
                ->horizontal()
                ->required()
                ->options([
                    2 => 'Committee',
                    3 => 'Student',
                    4 => 'Vendor',
                ]),
            ])
        ];
    }

    public function update(Guide $guide)
    {
        
        try{
            $fields = request()->validate([
                'ordering' => 'required',
                'guide_name' => 'required',
                'category' => 'required',
            ]);


            if ( ! empty( Guide::where( 'guide_name', $fields['guide_name'] )->whereNot('id', $guide->id)->first() ) 
                    || ! empty( Guide::where( 'ordering', $fields['ordering'] )->whereNot('id', $guide->id)->first() ) 
                ) {
                Toast::error( 'Guide or ordering already exists' );
            } else {
                $guide->update( $fields );
                Toast::success( 'Guide updated successfully' );
                return redirect()->route( 'platform.guide.list' );
            }

        }catch(Exception $e){
            return redirect()->route('platform.guide.list' . $e->getMessage());
        }
    }

    public function delete(Guide $guide)
    {
        try{
            $guide->delete();
            Toast::success('Guide deleted successfully');
            return redirect()->route('platform.guide.list');
        }catch(Exception $e){
            Toast::error('Error deleting guide' . $e->getMessage());
            return redirect()->route('platform.guide.list');
        }
    }
}

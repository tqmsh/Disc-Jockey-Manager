<?php

namespace App\Orchid\Screens;

use App\Models\TourElement;
use App\Models\TourScreen;
use App\Orchid\Layouts\ViewTourElementLayout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ViewAllTourElementScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'tourElements' => TourElement::latest()->filter(request(['portal','screen','search_input_by', 'name_filter']))->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'View All Tour Element Screen';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
        Link::make('Add New')
            ->route('platform.tour-element.create')
            ->icon('plus'),

         Button::make('Delete Selected Tour Elements')
             ->method('deleteTourElement')
             ->icon('trash')
             ->confirm('Are you sure you want to delete the selected beauty groups?'),
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

                    Select::make('portal')
                        ->title('Portal')
                        ->empty('No Selection')
                        ->options(array_filter([
                            0 => 'Local Admin',
                            1 => 'Student',
                            2 => 'Vendor' ])),

                    Select::make('screen')
                        ->title('Screen')
                        ->placeholder('Select the Screen for the Element')
                        ->fromModel(TourElement::class, 'screen', 'screen')
                        ->empty('Start typing to search...'),

                    Select::make('search_input_by')
                        ->title('Search By:')
                        ->options([
                            'title'   => 'Title',
                            'element' => 'Element',

                        ]),

                    Input::make('name_filter')
                        ->title('Enter:')
                        ->placeholder('No input')

                ]),


            Button::make('Filter')
                ->icon('filter')
                ->method('filter')
                ->type(Color::DEFAULT()),
        ]),
            ViewTourElementLayout::class
        ];
    }

    public function redirect(){
        if(request('type') == 'edit'){
            return redirect()->route('platform.tour-element.edit', request('tour_element_id'));
        }
    }

    public function deleteTourElement(){
        TourElement::whereIn('id', request('tourElement'))->delete();
        Toast::success('Selected Tour Elements Deleted Successfully');
    }

    public function filter(){

        return redirect()->route('platform.tour-element.list', request(['portal','screen', 'search_input_by', 'name_filter']));
    }

}

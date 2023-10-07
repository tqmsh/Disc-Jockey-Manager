<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;

use App\Orchid\Layouts\ViewLocaladminLayout;

class ViewLocaladminScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'localadmins' => Localadmin::latest('localadmins.created_at')
                                        ->filter(request(['country', 'state_province', 'school', 'school_board']))
                                        ->where('localadmins.account_status', 1)->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Local Admins';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Add New Local Admins')
                ->icon('plus')
                ->route('platform.localadmin.create'),

            Button::make('Delete Selected Local Admins')
                ->icon('trash')
                ->method('deleteLocaladmins')
                ->confirm(__('Are you sure you want to delete the selected local admins?')),

                
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.localadmin.list')
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
                        ->empty('No Selection')
                        ->help('Type in boxes to search')
                        ->fromModel(Localadmin::class, 'school', 'school'),

                    Select::make('country')
                        ->title('Country')
                        ->empty('No Selection')
                        ->fromModel(User::class, 'country', 'country'),

                    Select::make('school_board')
                        ->title('School Board')
                        ->empty('No Selection')
                        ->fromModel(School::class, 'school_board', 'school_board'),

                    Select::make('state_province')
                        ->title('State/Province')
                        ->empty('No Selection')
                        ->fromModel(School::class, 'state_province', 'state_province'),
                ]),
                
                Button::make('Filter')
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),

            ViewLocaladminLayout::class
        ];
    }

    public function filter(){
        return redirect()->route('platform.localadmin.list', request(['school', 'country', 'school_board', 'state_province']));

    }

    public function redirect($Localadmin){
        return redirect()-> route('platform.localadmin.edit', $Localadmin);
    }

    public function deleteLocaladmins(Request $request)
    {   
        //get all localadmins from post request
        $localadmins = $request->get('localadmins');
        
        try{

            //if the array is not empty
            if(!empty($localadmins)){

                //delete all selected localadmins
                User::whereIn('id', $localadmins)->delete();

                Toast::success('Selected local admins deleted succesfully');

            }else{
                Toast::warning('Please select local admins in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected local admins. Error Message: ' . $e->getMessage());
        }
    }
}

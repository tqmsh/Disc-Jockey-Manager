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
            'localadmins' => Localadmin::latest('localadmins.created_at')->filter(request(['country', 'state_province', 'school', 'school_board']))->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Local Admin List';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Add New Local Admin')
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
                        ->empty('No selection')
                        ->fromModel(Localadmin::class, 'school', 'school'),

                    Select::make('country')
                        ->title('Country')
                        ->empty('No selection')
                        ->fromModel(User::class, 'country', 'country'),

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

            ViewLocaladminLayout::class
        ];
    }

    public function filter(Request $request){
        return redirect('/admin/localadmins?' 
                    .'&school=' . $request->get('school')
                    .'&country=' . $request->get('country')
                    .'&school_board=' . $request->get('school_board')
                    .'&state_province=' . $request->get('state_province'));
    }

    public function deleteLocaladmins(Request $request)
    {   
        //get all localadmins from post request
        $localadmins = $request->get('localadmins');
        
        try{

            //if the array is not empty
            if(!empty($localadmins)){

                //loop through the localadmins and delete them from db
                foreach($localadmins as $localadmin){
                    Localadmin::where('id', $localadmin)->delete();
                }

                Alert::success('Selected local admins deleted succesfully');

            }else{
                Alert::warning('Please select local admins in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected local admins. Error Message: ' . $e);
        }
    }
}

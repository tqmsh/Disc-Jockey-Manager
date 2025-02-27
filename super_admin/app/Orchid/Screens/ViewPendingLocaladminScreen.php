<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\RoleUsers;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;

use App\Orchid\Layouts\ViewPendingLocaladminLayout;

class ViewPendingLocaladminScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'pending_localadmins' => Localadmin::latest('localadmins.created_at')
                                    ->filter(request(['country', 'state_province', 'school', 'school_board']))
                                    ->where('localadmins.account_status', 0)->paginate(min(request()->query('pagesize', 10), 100))
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Pending Local Admins';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Accept Selected Local Admins')
                ->icon('check')
                ->method('acceptLocaladmins')
                ->confirm(__('Are you sure you want to accept the selected local admins?')),

            Button::make('Reject Selected Local Admins')
                ->icon('trash')
                ->method('deleteLocaladmins')
                ->confirm(__('Are you sure you want to delete the selected local admins?')),
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
                        ->help('Type in boxes to search')
                        ->empty('No Selection')
                        ->fromModel(School::class, 'school_name', 'school_name'),

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

            ViewPendingLocaladminLayout::class
        ];
    }

    public function filter(){

        return redirect()->route('platform.pendinglocaladmin.list', request(['school', 'country', 'school_board', 'state_province']));
    }

    public function acceptLocaladmins(Request $request){

        //get all localadmins from post request
        $localadmins = $request->get('localadmins');
        
        try{
            //if the array is not empty
            if(!empty($localadmins)){

                //loop through the localadmins set account status to 1 and give them permissions to access dashboard
                foreach($localadmins as $localadmin_id){

                    User::where('id', $localadmin_id)->update(['account_status' => 1]);

                    Localadmin::where('user_id', $localadmin_id)->update(['account_status' => 1]);

                    RoleUsers::create([
                        'user_id' => $localadmin_id,
                        'role_id' => 2,
                    ]);
                }

                Toast::success('Selected local admins accepted succesfully');

            }else{
                Toast::warning('Please select local admins in order to accept them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to accept the selected local admins. Error Message: ' . $e->getMessage());
        }
    }




    public function deleteLocaladmins(Request $request){  

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
            Toast::error('There was a error trying to deleted the selected local admins. Error Message: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Orchid\Screens;

use Exception;
use Orchid\Screen\Screen;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
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
            'Localadmin' => Localadmin::paginate(10)
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
                ->method('deleteLocaladmins'),
                
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
            ViewLocaladminLayout::class
        ];
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

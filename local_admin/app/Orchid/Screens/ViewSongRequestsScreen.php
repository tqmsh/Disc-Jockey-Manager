<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Events;
use App\Models\School;
use App\Models\Vendors;
use App\Models\SongRequest;
use Orchid\Screen\Screen;
use App\Models\Categories;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewSongRequestsLayout;

class ViewSongRequestsScreen extends Screen
{

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Events $event): iterable
    {
        return [
            'songRequests' => SongRequest::where('event_id', $event-> id)->paginate(20),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Song Requests';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Button::make('Delete Song Request')
            ->icon('trash')
            ->method('delete'),
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
            ViewSongRequestsLayout::class,
        ];
    }

    public function redirect($event){
        return redirect() -> route('platform.songreq.edit', $event);
    }

    public function delete(Request $request)
    {   
        $songReqs = $request->get('songRequests');
        
        try{
            if(!empty($songReqs)){
                foreach($songReqs as $songReq){
                    SongRequest::where('id', $songReq)->delete();
                }
                Toast::success('Selected Song Request (s) deleted succesfully');

            return redirect()->route('platform.event.list');
            }else{
                Toast::warning('Please select Song Requests in order to delete them');
            }

        }catch(Exception $e){
            Alert::error('There was a error trying to deleted the selected Song Requests. Error Message: ' . $e);
        }
    }
}
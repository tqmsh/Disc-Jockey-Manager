<?php

namespace App\Orchid\Screens;

use Locale;
use Exception;
use App\Models\User;
use App\Models\Events;
use App\Models\Student;
use App\Models\EventAttendees;
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
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewRequestersLayout;
use App\Models\SongRequest;
use Orchid\Screen\Actions\ModalToggle;  

class ViewRequestersScreen extends Screen {

    public $event;
    public $songRequest_id;
    /**
     * Query data.
     *
     * @return array
     */
    public function query($songReq_id, Events $event): iterable
    {
        $songRequest= SongRequest::where('id',$songReq_id);
        $requesters= json_decode($songRequest -> first() -> requester_user_ids, true);

        return [
            'event' => $event,
            'requesters' =>  $requesters,
            'songRequest_id' => $songReq_id,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Requesters';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Add Requester')
                ->modal('reqModal')
                ->method('add')
                ->icon('plus'),

            Button::make('Delete Requester')
                ->icon('trash')
                ->method('delete'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.event.list')
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
            Layout::modal('reqModal', [
                Layout::rows([
                    
                    Select::make('student.id')
                    ->options(function(){
                        $requesters= json_decode(SongRequest::where('id', $this ->songRequest_id) -> first() -> requester_user_ids, true);
                        $arr= array();
                        foreach(Student::where('school_id', $this ->event-> school_id)-> get() as $student){
                            if(EventAttendees::where('user_id', $student -> user_id)->where('event_id', $this ->event->id)->exists() && !in_array($student -> user_id, $requesters)){
                                $arr[$student -> user_id]= 'ID: ' . $student -> user_id . ' , Name: ' . $student -> firstname . ' ' . $student-> lastname;
                            }
                        }
                        return $arr;
                    })
                    -> empty('Add a student'), 

                ]),
            ])
            ->title('Create Requester')
            ->applyButton('Add'),
             
        ViewRequestersLayout::class

        ];
    }

    public function delete($songRequest_id, Request $request)
    {
        $reqList= $request -> get('requesterList');
        $requesters= json_decode(SongRequest::where('id',$songRequest_id) -> first() -> requester_user_ids, true);

        try{
            if(!empty($reqList)){
                $requesters= array_diff($requesters, $reqList);
                $songRequest= SongRequest::where('id',$songRequest_id) -> first();
                $songRequest -> requester_user_ids= json_encode($requesters)    ;
                $songRequest -> save();

  
            }else{
                Toast::warning('Please select Requesters in order to delete them');
            }
        }catch(Exception $e){
           Alert::error('There was a error trying to deleted the selected Requesters. Error Message: ' . $e);
        } 
    }
    
    public function add($songRequest_id, Request $request)
    {
        $student_id= $request -> input('student.id');
        $requesters= json_decode(SongRequest::where('id',$songRequest_id) -> first() -> requester_user_ids, true);

        try{
            array_push($requesters, $student_id);
            $songRequest= SongRequest::where('id',$songRequest_id) -> first();
            $songRequest -> requester_user_ids= json_encode($requesters)    ;
            $songRequest -> save();

        }catch(Exception $e){
           Alert::error('There was a error trying to add the selected Requesters. Error Message: ' . $e);
        } 
    }

}      
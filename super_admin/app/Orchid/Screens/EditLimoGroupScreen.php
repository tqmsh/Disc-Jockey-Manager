<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Student;
use App\Models\LimoGroup;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use App\Models\LimoGroupMember;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;
use Illuminate\Support\Facades\Auth;

class EditLimoGroupScreen extends Screen
{
    public $limoGroup;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(LimoGroup $limoGroup): iterable
    {
        abort_if(Auth::user()->role != 1, 403, 'You are not authorized to view this page.');

        return [
            'limoGroup' => $limoGroup
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit: ' . $this->limoGroup->name ;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Update Limo Group')
                ->icon('plus')
                ->confirm('WARNING: Changing this limo group will remove the owner from their current limo group if they are in one. And if they own a limo group, it will delete it and all the memebers in it. Are you sure you want to change this limo group?')
                ->method('updateLimoGroup'),
            
            Button::make('Delete Limo Group')
                ->icon('trash')
                ->confirm('WARNING: Deleting a limo group will remove all the memebers in it. Are you sure you want to delete this limo group?')
                ->method('deleteLimoGroup'),
                
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.limo-groups')
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

                Select::make('creator_user_id')
                    ->title('Owner')
                    ->placeholder('Select the owner of this limo group')
                    ->options(Student::where('school_id', $this->limoGroup->school_id)->pluck('email', 'user_id'))
                    ->horizontal()
                    ->value($this->limoGroup->creator_user_id),

                Input::make('name')
                    ->title('Limo Group Name')
                    ->placeholder('Enter a name for your limo group')
                    ->horizontal()
                    ->value($this->limoGroup->name),

                DateTimer::make('date')
                    ->title('Date')
                    ->placeholder('Enter the date for your limo group')
                    ->horizontal()
                    ->value($this->limoGroup->date),
                
                Input::make('pickup_location')
                    ->title('Pickup Location')
                    ->placeholder('Enter the pickup location for your limo group')
                    ->horizontal()
                    ->value($this->limoGroup->pickup_location),
                
                Input::make('dropoff_location')
                    ->title('Dropoff Location')
                    ->placeholder('Enter the dropoff location for your limo group')
                    ->horizontal()
                    ->value($this->limoGroup->dropoff_location),
                
                DateTimer::make('depart_time')
                    ->title('Depart Time')
                    ->placeholder('Enter the depart time for your limo group')
                    ->enableTime()
                    ->horizontal()
                    ->value($this->limoGroup->depart_time),
                
                Datetimer::make('dropoff_time')
                    ->title('Dropoff Time')
                    ->placeholder('Enter the dropoff time for your limo group')
                    ->enableTime()
                    ->horizontal()
                    ->value($this->limoGroup->dropoff_time),
                
                Input::make('capacity')
                    ->title('Capacity')
                    ->type('number')
                    ->placeholder('Enter the capacity for your limo group')
                    ->horizontal()
                    ->value($this->limoGroup->capacity),
                
                TextArea::make('notes')
                    ->title('Notes')
                    ->placeholder('Enter any notes for your limo group')
                    ->help('Notes can be seen by all limo group members')
                    ->rows(8)
                    ->horizontal()
                    ->value($this->limoGroup->notes),                   
            ])
        ];
    }

    public function updateLimoGroup(Request $request, LimoGroup $limoGroup){

        $fields = $request->validate([
            'creator_user_id' => 'required',
            'name' => 'required',
            'date' => 'required',
            'capacity' => 'nullable',
            'pickup_location' => 'required',
            'dropoff_location' => 'required',
            'depart_time' => 'required',
            'dropoff_time' => 'required',
            'capacity' => 'required',
            'notes' => 'nullable',
        ]);

        if($fields['capacity'] < $limoGroup->capacity){
            Toast::error('Error Updating Limo Group. Capacity cannot be less than the number of members in the limo group.');
            return;
        }


        //check if user already owns a limo group
        $owned_limo_group = LimoGroup::where('creator_user_id', $fields['creator_user_id'])->first();

        //check if user is part of a limo group
        $user_limo_group = LimoGroupMember::where('invitee_user_id', $fields['creator_user_id'])->where('status', 1)->first();

        if($owned_limo_group != null && $owned_limo_group->id != $limoGroup->id){
            //delete the old limo group
            $owned_limo_group->delete();

        } elseif($user_limo_group != null && $user_limo_group->limo_group_id != $limoGroup->id){
            //remove them as a limo group member from old limo group
            $user_limo_group->delete();
        }

        try{
            $limoGroup->update($fields);
            Toast::success('Limo Group Updated');
            return redirect()->route('platform.limo-groups');
        } catch(Exception $e){
            Toast::error('Error Updating Limo Group. Error Code: ' . $e->getMessage());
        }
    }

    public function deleteLimoGroup(LimoGroup $limoGroup){
        try{
            $limoGroup->delete();
            Toast::success('Limo Group Deleted');
            return redirect()->route('platform.limo-groups');
        } catch(Exception $e){
            Toast::error('Error Deleting Limo Group. Error Code: ' . $e->getMessage());
        }
    }
}

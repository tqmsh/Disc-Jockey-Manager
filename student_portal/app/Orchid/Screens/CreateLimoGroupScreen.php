<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\LimoGroup;
use App\Models\LimoGroupMember;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;
use Illuminate\Support\Facades\Auth;

class CreateLimoGroupScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        abort_if(Auth::user()->role != 3, 403, 'You are not authorized to view this page.');
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Create a Limo Group';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create Limo Group')
                ->icon('plus')
                ->confirm('WARNING: Creating a limo group will remove you from your current limo group if you are in one. And, it will delete your previous limo group including all the memebers in it if you have created one. Are you sure you want to create a new limo group?')
                ->method('createLimoGroup')
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
                Input::make('name')
                    ->title('Limo Group Name')
                    ->placeholder('Enter a name for your limo group')
                    ->horizontal(),

                DateTimer::make('date')
                    ->title('Date')
                    ->placeholder('Enter the date for your limo group')
                    ->horizontal(),
                
                Input::make('pickup_location')
                    ->title('Pickup Location')
                    ->placeholder('Enter the pickup location for your limo group')
                    ->horizontal(),
                
                Input::make('dropoff_location')
                    ->title('Dropoff Location')
                    ->placeholder('Enter the dropoff location for your limo group')
                    ->horizontal(),
                
                DateTimer::make('depart_time')
                    ->title('Depart Time')
                    ->placeholder('Enter the depart time for your limo group')
                    ->enableTime()
                    ->horizontal(),
                
                Datetimer::make('dropoff_time')
                    ->title('Dropoff Time')
                    ->placeholder('Enter the dropoff time for your limo group')
                    ->enableTime()
                    ->horizontal(),
                
                Input::make('capacity')
                    ->title('Capacity')
                    ->type('number')
                    ->placeholder('Enter the capacity for your limo group')
                    ->horizontal(),
                
                TextArea::make('notes')
                    ->title('Notes')
                    ->placeholder('Enter any notes for your limo group')
                    ->help('Notes can be seen by all limo group members')
                    ->rows(8)
                    ->horizontal()                   
            ])
        ];
    }

    public function createLimoGroup(Request $request){

        try{
            $fields = $request->validate([
                'name' => 'required',
                'date' => 'required',
                'capacity' => 'nullable',
                'pickup_location' => 'required',
                'dropoff_location' => 'required',
                'depart_time' => 'required',
                'dropoff_time' => 'required',
                'notes' => 'nullable'
            ]);

            $fields['creator_user_id'] = Auth::user()->id;

            //check if user already owns a limo group
            $owned_limo_group = LimoGroup::where('creator_user_id', Auth::user()->id)->first();

            //check if user is part of a limo group
            $user_limo_group = LimoGroupMember::where('invitee_user_id', Auth::user()->id)->first();

            if($owned_limo_group){
                //delete the old limo group
                $owned_limo_group->delete();

            } elseif($user_limo_group){
                //remove them as a limo group member
                $user_limo_group->delete();
            }

            //create the new limo group
            LimoGroup::create($fields);

            Toast::success('Limo group created successfully!');
            return redirect()->route('platform.limo-groups');


        }catch(Exception $e){
            Toast::error('There was an error creating the limo group. Error code: ' . $e->getMessage());
        }

    }
}

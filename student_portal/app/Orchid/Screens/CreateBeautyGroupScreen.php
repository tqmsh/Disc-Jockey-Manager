<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\BeautyGroup;
use App\Models\BeautyGroupMember;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;
use Illuminate\Support\Facades\Auth;

class CreateBeautyGroupScreen extends Screen
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
        return 'Create a Beauty Group';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create Beauty Group')
                ->icon('plus')
                ->confirm('WARNING: Creating a beauty group will remove you from your current beauty group if you are in one. And if you own a beauty group, it will delete it and all the memebers in it. Are you sure you want to create a new beauty group?')
                ->method('createBeautyGroup')
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
                    ->title('Beauty Group Name')
                    ->placeholder('Enter a name for your beauty group')
                    ->horizontal()
                    ->required(),

                DateTimer::make('date')
                    ->title('Date')
                    ->placeholder('Enter the date for your beauty group')
                    ->horizontal()
                    ->required(),

                Input::make('pickup_location')
                    ->title('Pickup Location')
                    ->placeholder('Enter the pickup location for your beauty group')
                    ->horizontal()
                    ->required(),

                Input::make('dropoff_location')
                    ->title('Dropoff Location')
                    ->placeholder('Enter the dropoff location for your beauty group')
                    ->horizontal()
                    ->required(),

                DateTimer::make('depart_time')
                    ->title('Depart Time')
                    ->placeholder('Enter the depart time for your beauty group')
                    ->enableTime()
                    ->horizontal()
                    ->required(),

                Datetimer::make('dropoff_time')
                    ->title('Dropoff Time')
                    ->placeholder('Enter the dropoff time for your beauty group')
                    ->enableTime()
                    ->horizontal()
                    ->required(),

                Input::make('capacity')
                    ->title('Capacity')
                    ->type('number')
                    ->placeholder('Enter the capacity for your beauty group')
                    ->help('Including youself')
                    ->horizontal()
                    ->required(),

                TextArea::make('notes')
                    ->title('Notes')
                    ->placeholder('Enter any notes for your beauty group')
                    ->help('Notes can be seen by all beauty group members')
                    ->rows(8)
                    ->horizontal()
            ])
        ];
    }

    public function createBeautyGroup(Request $request){

        try{
            $fields = $request->validate([
                'name' => 'required',
                'date' => 'required',
                'capacity' => 'required|numeric|:min2',
                'pickup_location' => 'required',
                'dropoff_location' => 'required',
                'depart_time' => 'required',
                'dropoff_time' => 'required',
                'notes' => 'nullable'
            ]);

            $fields['creator_user_id'] = Auth::user()->id;
            $fields['school_id'] = Auth::user()->student->school_id;

            //check if user already owns a beauty group
            $owned_beauty_group = BeautyGroup::where('creator_user_id', Auth::user()->id)->first();

            //check if user is part of a beauty group
            $user_beauty_group = BeautyGroupMember::where('invitee_user_id', Auth::user()->id)->where('status', 1)->first();

            if($owned_beauty_group){
                //delete the old beauty group
                $owned_beauty_group->delete();

            } elseif($user_beauty_group){
                //remove them as a beauty group member
                $user_beauty_group->delete();
            }

            //create the new beauty group
            $beauty_group = BeautyGroup::create($fields);

            $beauty_group->decrement('capacity');

            $beauty_group->save();

            //add the user as a beauty group member
            BeautyGroupMember::create([
                'beauty_group_id' => $beauty_group->id,
                'invitee_user_id' => Auth::user()->id,
                'status' => 1
            ]);

            Toast::success('Beauty group created successfully!');
            return redirect()->route('platform.beauty-groups');


        }catch(Exception $e){
            Toast::error('There was an error creating the beauty group. Error code: ' . $e->getMessage());
            return back()->withInput();
        }

    }
}

<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\School;
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

class CreateLimoGroupScreen extends Screen
{
    public $limoGroup;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(LimoGroup $limoGroup, Request $request): iterable
    {
        $limoGroup->school = $request->input('school') ?? "";
        $limoGroup->creator_user_id = intval($request->input('creator_user_id')) ?? "";
        $limoGroup->country = $request->input('country') ?? "";
        $limoGroup->state_province = $request->input('state_province') ?? "";
        $limoGroup->county = $request->input('county') ?? "";
        $limoGroup->name = $request->input('name') ?? "";
        $limoGroup->date = $request->input('date') ?? "";
        $limoGroup->pickup_location = $request->input('pickup_location') ?? "";
        $limoGroup->dropoff_location = $request->input('dropoff_location') ?? "";
        $limoGroup->depart_time = $request->input('depart_time') ?? "";
        $limoGroup->dropoff_time = $request->input('dropoff_time') ?? "";
        $limoGroup->capacity = $request->input('capacity') ?? "";
        $limoGroup->notes = $request->input('notes') ?? "";

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
                ->confirm('WARNING: Creating a limo group will remove the owner from their current limo group if they are in one. And if they own a limo group, it will delete it and all the memebers in it. Are you sure you want to create a new limo group?')
                ->method('createLimoGroup'),

            Link::make('Back')
                ->route('platform.limo-groups')
                ->icon('arrow-left')
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
                    ->options(Student::pluck('email', 'user_id'))
                    ->horizontal()
                    ->required()
                    ->empty('Start typing to search...')
                    ->value($this->limoGroup->creator_user_id),

                Select::make('school')
                    ->title('School')
                    ->placeholder('Select the school for this limo group')
                    ->options(School::pluck('school_name', 'school_name'))
                    ->horizontal()
                    ->required()
                    ->empty('Start typing to search...')
                    ->value($this->limoGroup->school),

                Input::make('country')
                    ->title('Country')
                    ->placeholder('Enter the country for this limo group')
                    ->horizontal()
                    ->required()
                    ->value($this->limoGroup->country),

                Input::make('state_province')
                    ->title('State/Province')
                    ->placeholder('Enter the state/province for this limo group')
                    ->horizontal()
                    ->required()
                    ->value($this->limoGroup->state_province),

                Input::make('county')
                    ->title('County')
                    ->placeholder('Enter the county for this limo group')
                    ->horizontal()
                    ->required()
                    ->value($this->limoGroup->county),

                Input::make('name')
                    ->title('Limo Group Name')
                    ->placeholder('Enter a name for your limo group')
                    ->horizontal()
                    ->required()
                    ->value($this->limoGroup->name),

                DateTimer::make('date')
                    ->title('Date')
                    ->placeholder('Enter the date for your limo group')
                    ->horizontal()
                    ->required()
                    ->value($this->limoGroup->date),

                Input::make('pickup_location')
                    ->title('Pickup Location')
                    ->placeholder('Enter the pickup location for your limo group')
                    ->horizontal()
                    ->required()
                    ->value($this->limoGroup->pickup_location),

                Input::make('dropoff_location')
                    ->title('Dropoff Location')
                    ->placeholder('Enter the dropoff location for your limo group')
                    ->horizontal()
                    ->required()
                    ->value($this->limoGroup->dropoff_location),

                DateTimer::make('depart_time')
                    ->title('Depart Time')
                    ->placeholder('Enter the depart time for your limo group')
                    ->enableTime()
                    ->horizontal()
                    ->required()
                    ->value($this->limoGroup->depart_time),

                Datetimer::make('dropoff_time')
                    ->title('Dropoff Time')
                    ->placeholder('Enter the dropoff time for your limo group')
                    ->enableTime()
                    ->horizontal()
                    ->required()
                    ->value($this->limoGroup->dropoff_time),

                Input::make('capacity')
                    ->title('Capacity')
                    ->type('number')
                    ->placeholder('Enter the capacity for your limo group')
                    ->help('Including youself')
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

    public function createLimoGroup(Request $request){

        try{
            $fields = $request->validate([
                'creator_user_id' => 'required',
                'school' => 'required',
                'country' => 'required',
                'state_province' => 'required',
                'county' => 'required',
                'name' => 'required',
                'date' => 'required',
                'capacity' => 'nullable',
                'pickup_location' => 'required',
                'dropoff_location' => 'required',
                'depart_time' => 'required',
                'dropoff_time' => 'required',
                'notes' => 'nullable'
            ]);

            $fields['school_id'] = $this->getSchoolIDByReq($request);

            if(Student::where('user_id', $fields['creator_user_id'])->where('school_id', $fields['school_id'])->doesntExist()){
                throw New Exception('Selected Owner is not part of the selected school');
            }

            //check if user already owns a limo group
            $owned_limo_group = LimoGroup::where('creator_user_id', $fields['creator_user_id'])->first();

            //check if user is part of a limo group
            $user_limo_group = LimoGroupMember::where('invitee_user_id', $fields['creator_user_id'])->where('status', 1)->first();

            if($owned_limo_group){
                //delete the old limo group
                $owned_limo_group->delete();

            } elseif($user_limo_group){
                //remove them as a limo group member
                $user_limo_group->delete();
            }

            //create the new limo group
            $limo_group = LimoGroup::create($fields);

            $limo_group->decrement('capacity');

            $limo_group->save();

            //add the user as a limo group member
            LimoGroupMember::create([
                'limo_group_id' => $limo_group->id,
                'invitee_user_id' => $fields['creator_user_id'],
                'status' => 1
            ]);

            Toast::success('Limo group created successfully!');
            return redirect()->route('platform.limo-groups');


        }catch(Exception $e){
            Toast::error('There was an error creating the limo group. Error code: ' . $e->getMessage());
            return redirect()->route('platform.limo-groups.create', request(['creator_user_id', 'school', 'country', 'state_province', 'county', 'name', 'date', 'pickup_location', 'dropoff_location', 'depart_time', 'dropoff_time', 'capacity', 'notes']));
        }

    }

    private function getSchoolIDByReq($request){
        $school_id = School::where('school_name', $request->input('school'))
                            ->where('county', $request->input('county'))
                            ->where('state_province', $request->input('state_province'))
                            ->where('country', $request->input('country'))
                            ->get('id')->value('id');

        if(is_null($school_id)){

            throw New Exception('You are trying to enter a invalid school');

        } else{

            return $school_id;
        }
    }
}

<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\School;
use App\Models\Student;
use App\Models\BeautyGroup;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use App\Models\BeautyGroupMember;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\DateTimer;
use Illuminate\Support\Facades\Auth;

class CreateBeautyGroupScreen extends Screen
{
    public $beautyGroup;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(BeautyGroup $beautyGroup, Request $request): iterable
    {
        $beautyGroup->school = $request->input('school') ?? "";
        $beautyGroup->creator_user_id = intval($request->input('creator_user_id')) ?? "";
        $beautyGroup->country = $request->input('country') ?? "";
        $beautyGroup->state_province = $request->input('state_province') ?? "";
        $beautyGroup->county = $request->input('county') ?? "";
        $beautyGroup->name = $request->input('name') ?? "";
        $beautyGroup->date = $request->input('date') ?? "";
        $beautyGroup->pickup_location = $request->input('pickup_location') ?? "";
        $beautyGroup->dropoff_location = $request->input('dropoff_location') ?? "";
        $beautyGroup->depart_time = $request->input('depart_time') ?? "";
        $beautyGroup->dropoff_time = $request->input('dropoff_time') ?? "";
        $beautyGroup->capacity = $request->input('capacity') ?? "";
        $beautyGroup->notes = $request->input('notes') ?? "";


        return [
            'beautyGroup' => $beautyGroup
        ];
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
                ->confirm('WARNING: Creating a beauty group will remove the owner from their current beauty group if they are in one. And if they own a beauty group, it will delete it and all the memebers in it. Are you sure you want to create a new beauty group?')
                ->method('createBeautyGroup'),

            Link::make('Back')
                ->route('platform.beauty-groups')
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
                    ->placeholder('Select the owner of this beauty group')
                    ->options(Student::pluck('email', 'user_id'))
                    ->horizontal()
                    ->required()
                    ->empty('Start typing to search...')
                    ->value($this->beautyGroup->creator_user_id),

                Select::make('school')
                    ->title('School')
                    ->placeholder('Select the school for this beauty group')
                    ->options(School::pluck('school_name', 'school_name'))
                    ->horizontal()
                    ->required()
                    ->empty('Start typing to search...')
                    ->value($this->beautyGroup->school),

                Input::make('country')
                    ->title('Country')
                    ->placeholder('Enter the country for this beauty group')
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->country),

                Input::make('state_province')
                    ->title('State/Province')
                    ->placeholder('Enter the state/province for this beauty group')
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->state_province),

                Input::make('county')
                    ->title('County')
                    ->placeholder('Enter the county for this beauty group')
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->county),

                Input::make('name')
                    ->title('Beauty Group Name')
                    ->placeholder('Enter a name for your beauty group')
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->name),

                DateTimer::make('date')
                    ->title('Date')
                    ->placeholder('Enter the date for your beauty group')
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->date),

                Input::make('pickup_location')
                    ->title('Pickup Location')
                    ->placeholder('Enter the pickup location for your beauty group')
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->pickup_location),

                Input::make('dropoff_location')
                    ->title('Dropoff Location')
                    ->placeholder('Enter the dropoff location for your beauty group')
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->dropoff_location),

                DateTimer::make('depart_time')
                    ->title('Depart Time')
                    ->placeholder('Enter the depart time for your beauty group')
                    ->enableTime()
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->depart_time),

                Datetimer::make('dropoff_time')
                    ->title('Dropoff Time')
                    ->placeholder('Enter the dropoff time for your beauty group')
                    ->enableTime()
                    ->horizontal()
                    ->required()
                    ->value($this->beautyGroup->dropoff_time),

                Input::make('capacity')
                    ->title('Capacity')
                    ->type('number')
                    ->placeholder('Enter the capacity for your beauty group')
                    ->help('Including youself')
                    ->horizontal()
                    ->value($this->beautyGroup->capacity),

                TextArea::make('notes')
                    ->title('Notes')
                    ->placeholder('Enter any notes for your beauty group')
                    ->help('Notes can be seen by all beauty group members')
                    ->rows(8)
                    ->horizontal()
                    ->value($this->beautyGroup->notes),
            ])
        ];
    }

    public function createBeautyGroup(Request $request){

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

            //check if user already owns a beauty group
            $owned_beauty_group = BeautyGroup::where('creator_user_id', $fields['creator_user_id'])->first();

            //check if user is part of a beauty group
            $user_beauty_group = BeautyGroupMember::where('invitee_user_id', $fields['creator_user_id'])->where('status', 1)->first();

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
                'invitee_user_id' => $fields['creator_user_id'],
                'status' => 1
            ]);

            Toast::success('Beauty group created successfully!');
            return redirect()->route('platform.beauty-groups');


        }catch(Exception $e){
            Toast::error('There was an error creating the beauty group. Error code: ' . $e->getMessage());
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

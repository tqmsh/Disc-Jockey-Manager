<?php

namespace App\Orchid\Screens;

use App\Models\User;
use Orchid\Screen\TD;
use App\Models\Student;
use Orchid\Screen\Sight;
use App\Models\LimoGroup;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\LimoGroupMember;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\ModalToggle;

class ViewLimoGroupScreen extends Screen
{
    public $limo_group;
    public $owned_limo_group;
    public $limo_group_members;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $current_limo_group =  LimoGroup::find(LimoGroupMember::where('invitee_user_id', Auth::user()->id)->pluck('limo_group_id')->value('limo_group_id'));
        $owned_limo_group = LimoGroup::where('creator_user_id', Auth::user()->id)->first();

        return [
            'owned_limo_group' => ($owned_limo_group != null) ? $owned_limo_group : null,
            'current_limo_group' => ($current_limo_group != null) ? $current_limo_group : null,
            'current_limo_group_members' => ($current_limo_group != null ? $current_limo_group->members : ($owned_limo_group != null)) ? $owned_limo_group->members : null,
            'default' => null,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Limo Groups';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Create a New Limo Group')
                ->icon('plus')
                ->route('platform.limo-groups.create'),
            
            ($this->owned_limo_group != null && $this->owned_limo_group->creator_user_id == Auth::user()->id) ? 
                
            ModalToggle::make('Invite Members')
                ->modal('inviteMembersModal')
                ->method('inviteMembers')
                ->icon('user-follow')
                : 
            Link::make('Back')
            ->icon('arrow-left')
            ->route('platform.limo-groups'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        if($this->query('owned_limo_group') != null){
            $this->limo_group = 'owned_limo_group';
        }elseif($this->query('current_limo_group') != null){
            $this->limo_group = 'current_limo_group';
        }else{
            $this->limo_group = 'default';
        }

        return [

          Layout::modal('inviteMembersModal', [
                Layout::rows([

                    ($this->owned_limo_group != null && $this->owned_limo_group->creator_user_id == Auth::user()->id) ?

                    //make a Select class only including users who are not already in the limo group and are students at their school
                    Select::make('invitee_user_ids')
                        ->title('Invite Members')
                        ->placeholder('Select a user')
                        ->help('Select a student to invite to the limo group.')
                        ->required()
                        ->fromQuery(Student::where('school_id', Auth::user()->student->school_id)->whereNot('user_id', Auth::user()->id)->whereNotIn('user_id', LimoGroupMember::where('limo_group_id', $this->owned_limo_group->id)->get('invitee_user_id')), 'email', 'user_id')
                        ->multiple()
                        ->popover('If you do not see a student you would like to invite, please enter their email and add them.')
                        ->allowAdd() : Input::make('unauthorized', 'You are not authorized to invite members to this limo group.'),
                ]),
          ])->title('Invite Members')
            ->applyButton('Invite'),

          Layout::tabs([
                'Limo Group Info' => [
                    Layout::legend($this->limo_group, [
                        Sight::make('creator_user_id', 'Owner')->render(function (LimoGroup $limoGroup = null) {

                            if($limoGroup == null){
                                return 'N/A';
                            }elseif($limoGroup->owner->user_id == Auth::user()->id){
                                return 'You';
                            }else{
                                return $limoGroup->owner->firstname . ' ' . $limoGroup->owner->lastname;
                            }
                        }),
                        Sight::make('name', 'Name')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return 'N/A';
                            }else{
                                return $limoGroup->name;
                            }
                        }),
                        Sight::make('capacity', 'Capacity')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return 'N/A';
                            }else{
                                return $limoGroup->capacity;
                            }
                        }),
                        Sight::make('date', 'Date')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return 'N/A';
                            }else{
                                return $limoGroup->date;
                            }
                        }),
                        Sight::make('pickup_location', 'Pickup Location')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return 'N/A';
                            }else{
                                return $limoGroup->pickup_location;
                            }
                        }),
                        Sight::make('dropoff_location', 'Dropoff Location')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return 'N/A';
                            }else{
                                return $limoGroup->dropoff_location;
                            }
                        }),
                        Sight::make('depart_time', 'Depart Time')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return 'N/A';
                            }else{
                                return $limoGroup->depart_time;
                            }
                        }),
                        Sight::make('dropoff_time', 'Dropoff Time')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return 'N/A';
                            }else{
                                return $limoGroup->dropoff_time;
                            }
                        }),
                        Sight::make('notes', 'Notes')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return 'N/A';
                            }else{
                                return $limoGroup->notes;
                            }
                        }),
                        Sight::make('', 'Actions')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null || $limoGroup->owner->user_id != Auth::user()->id){
                                return 'Only the owner can edit this group.';
                            }else{
                                return 
                                    Button::make('Edit')
                                    ->icon('pencil')
                                    ->method('redirect', ['limoGroup_id' => $limoGroup->id])
                                    ->type(Color::PRIMARY());
                                
                            }
                        }),
                    ]),
    
                ],
                'Members in Limo Group' => [
                    Layout::table('current_limo_group_members', [
                        TD::make()
                            ->render(function (LimoGroupMember $student){
                                return CheckBox::make('students[]')
                                    ->value($student->invitee_user_id)
                                    ->checked(false);
                            })->width('5%'),

                        TD::make('firstname', 'First Name')
                            ->render(function (LimoGroupMember $student) {
                                return e($student->user->firstname);
                            })->width('10%'),
                        TD::make('lastname', 'Last Name')
                            ->render(function (LimoGroupMember $student) {
                                return e($student->user->lastname);
                            })->width('10%'),
                        TD::make('email', 'Email')->width('105')
                            ->render(function (LimoGroupMember $student) {
                                return e($student->user->email);
                            })->width('25%%'),
                            
                        TD::make('phonenumber', 'Phone Number')
                            ->render(function (LimoGroupMember $student) {
                                return e($student->user->phonenumber);
                            }),

                        TD::make('status', 'Invite Status')
                            ->render(function (LimoGroupMember $student) {
                                return 
                                    ($student->status == 0) ? '<i class="text-warning">●</i> Pending' 
                                    : (($student->status == 1) ? '<i class="text-success">●</i> Approved' 
                                    : '<i class="text-danger">●</i> Rejected');
                                        }),

                        TD::make('paid', 'Payment Status')
                            ->render(function (LimoGroupMember $student) {
                                return ($student->paid == 0) ? '<i class="text-danger">●</i> Unpaid' : '<i class="text-success">●</i> Paid' ;
                            }),
                                                

                    ])
                ],
                'Limo Group Invitations' => [
                ],

            ]),
        ];
    }

    public function redirect(){
        return redirect()->route('platform.limo-groups.edit', request('limoGroup_id'));
    }

    public function inviteMembers(){
        try{
            $invitee_user_ids = request('invitee_user_ids');
            $limo_group = LimoGroup::where('creator_user_id', Auth::user()->id)->first();
            
            foreach($invitee_user_ids as $invitee_user_id){

                    if(is_numeric($invitee_user_id)){

                        $current_limo_group = new LimoGroupMember;
                        $current_limo_group->limo_group_id = $limo_group->id;
                        $current_limo_group->invitee_user_id = $invitee_user_id;
                        $current_limo_group->save();
                        
                    } else{
                        //send email to the email entered by user
                    }
                }
            } catch(\Exception $e){
                Toast::error('Something went wrong. Error Code:' . $e->getMessage());
            } 
    }
}

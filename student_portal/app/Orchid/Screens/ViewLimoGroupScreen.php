<?php

namespace App\Orchid\Screens;

use App\Models\User;
use Orchid\Screen\Sight;
use App\Models\LimoGroup;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\LimoGroupMember;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\ModalToggle;

class ViewLimoGroupScreen extends Screen
{
    public $limo_group;
    public $owned_limo_group;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'owned_limo_group' => LimoGroup::where('creator_user_id', Auth::user()->id)->first(),
            'current_limo_group' => LimoGroupMember::where('invitee_user_id', Auth::user()->id)->first(),
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
            Link::make('Create Limo Group')
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
                    //make a Select class only including users who are not already in the limo group and are students at their school
                    Select::make('invitee_user_ids')
                        ->title('Invite Members')
                        ->placeholder('Select a user')
                        ->help('Select a student to invite to the limo group.')
                        ->required()
                        ->fromModel(User::class, 'firstname')
                        ->multiple()
                        ->popover('If you do not see a student you would like to invite, please enter their email and add them.')
                        ->allowAdd(),
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
                ],
                'Limo Group Invitations' => [
                ],

            ]),
        ];
    }

    public function redirect(){
        return redirect()->route('platform.limo-groups.edit', request('limoGroup_id'));
    }

    public function inviteMembers(LimoGroup $limo_group){
        $invitee_user_ids = request('invitee_user_ids');
                
    }
}

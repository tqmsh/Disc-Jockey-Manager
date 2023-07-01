<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Sight;
use App\Models\LimoGroup;
use App\Models\LimoGroupMember;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;

class ViewLimoGroupScreen extends Screen
{
    public $limo_group;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $default = new LimoGroup([
            'id' => 'N/A',
            'creator_user_id' => 'N/A',
            'name' => 'N/A',
            'capacity' => 'N/A',
            'date' => 'N/A',
            'pickup_location' => 'N/A',
            'dropoff_location' => 'N/A',
            'depart_time' => 'N/A',
            'dropoff_time' => 'N/A',
            'notes' => 'N/A',
        ]);
        return [
            'owned_limo_group' => LimoGroup::where('creator_user_id', Auth::user()->id)->first(),
            'current_limo_group' => LimoGroupMember::where('invitee_user_id', Auth::user()->id)->first(),
            'default' => $default,
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
                    ]),
    
                ],
                'Members in Limo Group' => [
                ],
                'Limo Group Invitations' => [
                ],

            ]),
        ];
    }
}

<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Sight;
use App\Models\LimoGroup;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;

class ViewLimoGroupScreen extends Screen
{
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
        return [
          Layout::tabs([
                'Limo Group Info' => [
                    Layout::legend('owned_limo_group', [
                        Sight::make('creator_user_id', 'Owner')->render(function (LimoGroup $limoGroup) {

                            if($limoGroup->owner->user_id == Auth::user()->id){
                                return 'You';
                            }else{
                                return $limoGroup->owner->firstname . ' ' . $limoGroup->owner->lastname;
                            }
                        }),
                        Sight::make('name', 'Name'),
                        Sight::make('capacity', 'Capacity'),
                        Sight::make('date', 'Date'),
                        Sight::make('pickup_location', 'Pickup Location'),
                        Sight::make('dropoff_location', 'Dropoff Location'),
                        Sight::make('depart_time', 'Depart Time'),
                        Sight::make('dropoff_time', 'Dropoff Time'),
                        Sight::make('notes', 'Notes'),
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

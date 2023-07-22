<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;
use Illuminate\Support\Facades\Auth;

class ViewLimoGroupMembersLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'members';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
                
            TD::make('firstname', 'First Name')
                ->render(function ($member) {
                    return e($member->user->firstname);
                }),
            
            TD::make('lastname', 'Last Name')
                ->render(function ($member) {
                    return e($member->user->lastname);
                }),

            TD::make('email', 'Email')
                ->render(function ($member) {
                    return e($member->user->email);
                }),

            TD::make('phonenumber', 'Phone Number')
                ->render(function ($member) {
                    return e($member->user->phonenumber);
                }),

            TD::make('status', 'Invite Status')
                ->render(function ($member) {
                    return 
                        ($member->status == 0) ? '<i class="text-warning">●</i> Pending' 
                        : (($member->status == 1) ? '<i class="text-success">●</i> Accepted' 
                        : '<i class="text-danger">●</i> Rejected');
                            }),

            TD::make('paid', 'Payment Status')
                ->render(function ($member) {
                    return ($member->paid == 0) ? '<i class="text-danger">●</i> Unpaid' : '<i class="text-success">●</i> Paid' ;
                }),

        ];
    }
}

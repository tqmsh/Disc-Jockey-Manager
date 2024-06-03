<?php

namespace App\Orchid\Screens;

use App\Models\User;
use App\Models\Vendors;
use Orchid\Screen\TD;
use App\Models\Student;
use Orchid\Screen\Sight;
use App\Models\LimoGroup;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use App\Models\LimoGroupBid;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Models\VendorPackage;
use App\Models\LimoGroupMember;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Notifications\GroupInvitation;
use Orchid\Screen\Actions\ModalToggle;
use App\Notifications\GeneralNotification;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Fields\SimpleMDE;

class ViewLimoGroupScreen extends Screen
{
    public $limo_group;
    public $owner = false;
    public $owned_limo_group;
    public $limo_group_member_members;
    public $current_limo_group_members;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $current_limo_group =  LimoGroup::whereNot('creator_user_id', Auth::id())->where('id', LimoGroupMember::where('invitee_user_id', Auth::id())->where('status', 1)->get('limo_group_id')->value('limo_group_id'))->first();
        $owned_limo_group = LimoGroup::where('creator_user_id', Auth::user()->id)->first();

        return [
            'owned_limo_group' => ($owned_limo_group != null) ? $owned_limo_group : null,
            'current_limo_group' => ($current_limo_group != null) ? $current_limo_group : null,
            'current_limo_group_members' => ($current_limo_group != null) ? $current_limo_group->members()->paginate(min(request()->query('pagesize', 10), 100)) 
                                    : (($owned_limo_group != null) ? $owned_limo_group->members()->paginate(min(request()->query('pagesize', 10), 100))
                                    : []),
            'limo_group_invitations' => LimoGroupMember::where('invitee_user_id', Auth::user()->id)->where('status', 0)->paginate(min(request()->query('pagesize', 10), 100)),
            'limoBids' => LimoGroupBid::where('limo_group_id', ($owned_limo_group != null) ? $owned_limo_group->id : 0)->where('status', 0)->paginate(min(request()->query('pagesize', 10), 100)),
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
            DropDown::make('Actions')
                ->icon('arrow-down')
                ->list([
                    Link::make('Create a New Limo Group')
                    ->icon('plus')
                    ->route('platform.limo-groups.create'),
                    
                    Button::make('Leave Limo Group')
                        ->icon('logout')
                        ->method('leaveLimoGroup', 
                            ['current_limo_group' => ($this->query()['current_limo_group'] != null) ? $this->query()['current_limo_group']->id : null,
                            'owned_limo_group' => $this->query()['owned_limo_group'] != null ? $this->query()['owned_limo_group']->id : null]
                        )
                        ->confirm('WARNING: If you are the owner of the limo group, the entire group will be deleted. Are you sure you want to leave this limo group?'),
                    
                    ModalToggle::make('Invite Members')
                        ->modal('inviteMembersModal')
                        ->method('inviteMembers')
                        ->icon('user-follow')
                        ->canSee($this->owned_limo_group != null),

                    Button::Make('Remove Selected Members')
                        ->icon('unfollow')
                        ->method('removeSelectedMembers')
                        ->confirm('Are you sure you want to remove the selected members?')
                        ->canSee($this->owned_limo_group != null),

                    Button::make('Delete Selected Received Invitations')
                        ->icon('trash')
                        ->method('deleteSelectedInvitations')
                        ->confirm('Are you sure you want to delete the selected invitations?'),
                ]),
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
        if($this->query()['owned_limo_group'] != null){
            $this->limo_group = 'owned_limo_group';
            $this->owner = true;
        }elseif($this->query()['current_limo_group'] != null){
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
                                return '';
                            }elseif($limoGroup->owner->user_id == Auth::user()->id){
                                return 'You';
                            }else{
                                return $limoGroup->owner->firstname . ' ' . $limoGroup->owner->lastname;
                            }
                        }),
                        Sight::make('name', 'Name')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return '';
                            }else{
                                return $limoGroup->name;
                            }
                        }),
                        Sight::make('capacity', 'Capacity')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return '';
                            }else{
                                return $limoGroup->capacity;
                            }
                        }),
                        Sight::make('date', 'Date')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return '';
                            }else{
                                return $limoGroup->date;
                            }
                        }),
                        Sight::make('pickup_location', 'Pickup Location')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return '';
                            }else{
                                return $limoGroup->pickup_location;
                            }
                        }),
                        Sight::make('dropoff_location', 'Dropoff Location')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return '';
                            }else{
                                return $limoGroup->dropoff_location;
                            }
                        }),
                        Sight::make('depart_time', 'Depart Time')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return '';
                            }else{
                                return $limoGroup->depart_time;
                            }
                        }),
                        Sight::make('dropoff_time', 'Dropoff Time')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return '';
                            }else{
                                return $limoGroup->dropoff_time;
                            }
                        }),
                        Sight::make('notes', 'Notes')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null){
                                return '';
                            }else{
                                return $limoGroup->notes;
                            }
                        }),
                        Sight::make('', 'Actions')->render(function(LimoGroup $limoGroup = null){
                            if($limoGroup == null || $limoGroup->owner->user_id != Auth::user()->id){
                                return '';
                            }else{
                                return 
                                    Button::make('Edit')
                                    ->icon('pencil')
                                    ->method('redirect', ['limoGroup_id' => $limoGroup->id])
                                    ->type(Color::PRIMARY());
                                
                            }
                        }),
                    ]),

                    Layout::rows([
                        Input::make('subject')
                            ->title('Subject')
                            ->placeholder('Message subject line')
                            ->help('Enter the subject line for your message'),

                        Select::make('users.')
                            ->title('Recipients')
                            ->multiple()
                            ->placeholder('Email addresses')
                            ->help('Enter the users that you would like to send this message to.')
                            ->options(function (){
                                $members = [];

                                foreach($this->query()['current_limo_group_members'] as $member){
                                    $members[$member->user->email] = $member->user->firstname . ' '  . $member->user->lastname;
                                }

                                return $members;
                            }),

                        SimpleMDE::make('content')
                            ->title('Content')
                            ->toolbar(["text", "color", "header", "list", "format", "align", "link", ])
                            ->placeholder('Insert text here ...')
                            ->help('Add the content for the message that you would like to send.'),

                        Button::make('Send')
                            ->method('sendMessage')
                            ->icon('paper-plane')
                            ->type(Color::DARK())
                    ])
    
                ],
                'Members in Group' => [
                    Layout::table('current_limo_group_members', [

                        TD::make()
                            ->render(function (LimoGroupMember $student){
                                return ($student->limoGroup->creator_user_id == Auth::user()->id) ? 
                                 CheckBox::make('members[]')
                                    ->value($student->id)
                                    ->checked(false) : ''; 
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
                            })->width('20%'),
                            
                        TD::make('phonenumber', 'Phone Number')
                            ->render(function (LimoGroupMember $student) {
                                return e($student->user->phonenumber);
                            }),

                        TD::make('status', 'Invite Status')
                            ->render(function (LimoGroupMember $student) {
                                return 
                                    ($student->status == 0) ? '<i class="text-warning">●</i> Pending' 
                                    : (($student->status == 1) ? '<i class="text-success">●</i> Accepted' 
                                    : '<i class="text-danger">●</i> Rejected');
                                        }),

                        TD::make('paid', 'Payment Status')
                            ->render(function (LimoGroupMember $student) {
                                return (($student->limoGroup->creator_user_id != Auth::id()) ? 'Confidential' : (($student->paid == 0) ? '<i class="text-danger">●</i> Unpaid' : '<i class="text-success">●</i> Paid')) ;
                            }),

                        TD::make('created_at', 'Invited At')
                            ->render(function (LimoGroupMember $student) {
                                return e($student->created_at);
                            }),                        

                    ])
                ],
                'Received Limo Group Invitations' => [
                    Layout::table('limo_group_invitations', [

                        TD::make('actions', 'Actions')
                            ->render(function (LimoGroupMember $student){
                                return CheckBox::make('invitations[]')
                                    ->value($student->id)
                                    ->checked(false);
                            }),

                        TD::make()
                            ->align(TD::ALIGN_LEFT)
                            ->width('100px')
                            ->render(function(LimoGroupMember $student_limo_member){
                                return Button::make('Accept')
                                ->confirm('WARNING: Joining a limo group will remove you from your current limo group if you are in one. And if you own a limo group, it will delete it and all the memebers in it. Are you sure you want to join this limo group?')
                                ->method('updateInvitation', ['limo_group_member_id' => $student_limo_member->id, 'choice' => 1])
                                ->icon('check')->type(Color::SUCCESS())->class('btn btn-success btn-rounded'); 
                                }), 

                        TD::make()
                            ->align(TD::ALIGN_LEFT)
                            ->width('100px')
                            ->render(function(LimoGroupMember $student_limo_member){
                                return Button::make('Reject')->method('updateInvitation', ['limo_group_member_id' => $student_limo_member->id, 'choice' => 2])->icon('close')->type(Color::DANGER()); 
                                }),

                        TD::make('owner', 'Owner')
                            ->render(function (LimoGroupMember $student) {
                                return e(LimoGroup::find($student->limo_group_id)->owner->firstname . ' ' . LimoGroup::find($student->limo_group_id)->owner->lastname);
                            }),
                        
                        TD::make('capacity', 'Capacity')
                            ->render(function (LimoGroupMember $student) {
                                return e(LimoGroup::find($student->limo_group_id)->capacity);
                            }),
                        
                        TD::make('date', 'Date')
                            ->render(function (LimoGroupMember $student) {
                                return e(LimoGroup::find($student->limo_group_id)->date);
                            }),
                        
                        TD::make('pickup_location', 'Pickup Location')
                            ->render(function (LimoGroupMember $student) {
                                return e(LimoGroup::find($student->limo_group_id)->pickup_location);
                            }),
                        
                        TD::make('dropoff_location', 'Dropoff Location')
                            ->render(function (LimoGroupMember $student) {
                                return e(LimoGroup::find($student->limo_group_id)->dropoff_location);
                            }),
                        
                        TD::make('depart_time', 'Depart Time')
                            ->render(function (LimoGroupMember $student) {
                                return e(LimoGroup::find($student->limo_group_id)->depart_time);
                            }),
                        
                        TD::make('dropoff_time', 'Dropoff Time')
                            ->render(function (LimoGroupMember $student) {
                                return e(LimoGroup::find($student->limo_group_id)->dropoff_time);
                            }),

                        TD::make('notes', 'Notes')
                            ->render(function (LimoGroupMember $student) {
                                return e(LimoGroup::find($student->limo_group_id)->notes);
                            }),
                    ])
                ],

                'Pending Bids' => ($this->owner) ?
                    
                    Layout::table('limoBids', [

                            TD::make()
                                ->align(TD::ALIGN_LEFT)
                                ->width('100px')
                                ->render(function(LimoGroupBid $bid){
                                    return Button::make('Accept')                                
                                    ->confirm('WARNING: Accepting a bid will remove all other bids accepted from the limo group. Are you sure you want to accept this bid?')
                                    ->method('updateBid', ['bid_id' => $bid->id, 'choice' => 1, 'limo_group_id' => $bid->limo_group_id])->icon('check')->type(Color::SUCCESS()); 
                                    }), 

                            TD::make()
                                ->align(TD::ALIGN_LEFT)
                                ->width('100px')
                                ->render(function(LimoGroupBid $bid){
                                    return Button::make('Reject')->method('updateBid', ['bid_id' => $bid->id, 'choice' => 2])->icon('close')->type(Color::DANGER()); 
                                    }), 

                            TD::make('company_name', 'Company')
                                ->render(function($bid){
                                    return Link::make($bid->company_name)
                                        ->href($bid->url);
                                }),
                                
                            TD::make('category_id', 'Category')
                                ->render(function($bid){
                                    return e(Categories::find($bid->category_id)->name);
                                }),

                            TD::make('package_id', 'Package')
                                ->render(function($bid){
                                    return e(VendorPackage::find($bid->package_id)->package_name);
                                }),

                            TD::make('package_id', 'Description')
                                ->width('300')
                                ->render(function($bid){
                                    return e(VendorPackage::find($bid->package_id)->description);
                                }),

                            TD::make('package_id', 'Price - $USD')
                                ->width('110')
                                ->align(TD::ALIGN_CENTER)
                                ->render(function($bid){
                                    return e('$' . number_format(VendorPackage::find($bid->package_id)->price));
                                }),

                            TD::make('package_id', 'Package URL')
                                ->width('200')
                                ->render(function($bid){
                                    return Link::make(VendorPackage::find($bid->package_id)->url)->href(VendorPackage::find($bid->package_id)->url);
                                }),

                        TD::make('notes', 'Vendor Notes')
                                ->width('300')
                                ->render(function($bid){
                                    return e($bid->notes);
                                }),

                        TD::make('contact_instructions', 'Contact Info')
                                ->width('300')
                                ->render(function($bid){
                                    return e($bid->contact_instructions);

                                }),

                    ])
                
                : null

            ]),
        ];
    }

    public function sendMessage(Request $request)
    {
        try {
            $data = $request->validate([
                'subject' => 'required|min:6|max:50',
                'users'   => 'required',
                'content' => 'required|min:10',
            ]);

            $data['sender'] = Auth::user();

            Mail::send(
                'emails.generalEmail', $data, 
                function (Message $message) use ($request) {
                $message->subject($request->get('subject'));

                foreach ($request->get('users') as $email) {
                    $message->to($email);
                }
            });

            Toast::info('Your email message has been sent successfully.');

        } catch (\Exception $e) {
            Toast::error($e->getMessage());
        }
    }

    public function updateBid()
    {
        try {
            $bid = LimoGroupBid::find(request('bid_id'));
            $bid->status = request('choice');
            $new_vendor = User::find($bid->user_id);
            $vendor = Vendors::where('user_id', $bid->user_id)->first();
            $adPrice = 50;

            if(request('choice') == 1){
                $vendor->decrement('credits', $adPrice);

                $old_limo_group_bid = LimoGroupBid::where('limo_group_id', request('limo_group_id'))->where('status', 1)->first();
                if($old_limo_group_bid != null){
                    $old_limo_group_bid->status = 0;
                    $old_limo_group_bid->save();
                    $old_vendor = User::find($old_limo_group_bid->user_id);
                    $old_vendor->notify(new GeneralNotification([
                        'title' => 'Limo Group Bid Changed',
                        'message' => 'Your bid for the ' . $old_limo_group_bid->limoGroup->name . ' limo group has been changed. Please contact the limo group owner for more information.',
                        'action' => '/admin/bids/history',
                    ]));
                    $new_vendor->notify(new GeneralNotification([
                        'title' => 'Limo Group Bid Accepted',
                        'message' => 'Your bid for the ' . $bid->limoGroup->name . ' limo group has been accepted. Please contact the limo group owner for more information.',
                        'action' => '/admin/bids/history',
                    ]));
                } else{
                    $new_vendor->notify(new GeneralNotification([
                        'title' => 'Limo Group Bid rejected',
                        'message' => 'Your bid for the ' . $bid->limoGroup->name . ' limo group has been rejected. Please contact the limo group owner for more information.',
                        'action' => '/admin/bids/history',
                    ]));
                }
            }

            $bid->save();
            Toast::success('Bid updated successfully!');
            return redirect()->route('platform.limo-groups');
        } catch (\Exception $e) {
            Toast::error($e->getMessage());
            return redirect()->route('platform.limo-groups');
        }
    }

    public function deleteSelectedInvitations(){
        $invitations = request('invitations');

        if($invitations != null){
            foreach($invitations as $invitation){
                LimoGroupMember::find($invitation)->delete();
            }
            Toast::success('You have deleted the selected invitations successfully');
        } else{
            Toast::error('You have not selected any invitations to delete');
        }
    }

    public function leaveLimoGroup(){

        try{
            if(request('current_limo_group') != null){
                
                LimoGroupMember::where('invitee_user_id', Auth::id())->where('status', 1)->where('limo_group_id', request('current_limo_group'))->delete();
                $current_limo_group = LimoGroup::find(request('current_limo_group'));
                $current_limo_group->increment('capacity');
                $current_limo_group->save();
                Toast::success('You have left the limo group successfully');

            } elseif(request('owned_limo_group') != null){

                $owned_limo_group = LimoGroup::find(request('owned_limo_group'));
                $owned_limo_group->delete();
                Toast::success('You have deleted the limo group successfully');
            } else{
                Toast::error('You are not in a limo group');
            }

            return redirect()->route('platform.limo-groups');
        } catch(\Exception $e){
            Toast::error($e->getMessage());
            return redirect()->route('platform.limo-groups');
        }

    }

    public function redirect(){
        return redirect()->route('platform.limo-groups.edit', request('limoGroup_id'));
    }

    public function updateInvitation(){
        $limo_group_member = LimoGroupMember::find(request('limo_group_member_id'));
        $limo_group_member->status = request('choice');

        //check if user already owns a limo group
        $owned_limo_group = LimoGroup::where('creator_user_id', Auth::user()->id)->first();

        //check if user is part of a limo group
        $user_limo_group = LimoGroupMember::where('invitee_user_id', Auth::user()->id)->where('status', 1)->first();

        if($owned_limo_group){
            //delete the old limo group
            $owned_limo_group->delete();

        } elseif($user_limo_group){
            //remove them as a limo group member
            $user_limo_group->delete();
        }

        if(request('choice') == 1){
            //update the limo group capacity
            $limo_group = LimoGroup::find($limo_group_member->limoGroup->id);
            $limo_group->decrement('capacity');
            $limo_group->save();
        }

        $limo_group_member->save();
        Toast::success('Invitation updated successfully!');
        return redirect()->route('platform.limo-groups');
    }

    public function inviteMembers(){
        try{
                $invitee_user_ids = request('invitee_user_ids');
                $limo_group_member = LimoGroup::where('creator_user_id', Auth::user()->id)->first();
                
                foreach($invitee_user_ids as $invitee_user_id){

                        if(is_numeric($invitee_user_id)){

                            $user = User::find($invitee_user_id);
                            $user->notify(new GroupInvitation([
                                'title' => 'You have been invited to join a limo group!',
                                'message' => 'You have been invited to join a limo group by ' . Auth::user()->firstname . ' ' . Auth::user()->lastname . '. Please check your limo group invitations page to accept or reject the invitation.',
                                'action' => '/admin/limo-groups/'
                            ]));

                            $current_limo_group = new LimoGroupMember;
                            $current_limo_group->limo_group_id = $limo_group_member->id;
                            $current_limo_group->invitee_user_id = $invitee_user_id;
                            $current_limo_group->save();
                            
                        } else{
                            //send email to the email entered by user

                            $data = [
                                'inviter_name' => Auth::user()->firstname . ' ' . Auth::user()->lastname,
                                'inviter_school' => Auth::user()->student->school,
                                'limo_group_name' => $limo_group_member->name,
                            ];

                            Mail::send(
                                'emails.limoGroup', $data, function (Message $message) use ($invitee_user_id) {
                                    $message->subject('You have been invited to join a limo group!');
                                    $message->to($invitee_user_id);
                                }
                            );
                        }
                    }

                Toast::success('You have successfully invited ' . count($invitee_user_ids) . ' member(s) to your limo group!');
            } catch(\Exception $e){
                Toast::error('Something went wrong. Error Code:' . $e->getMessage());
            } 
    }

    public function removeSelectedMembers() {
        $ownedGroup = LimoGroup::where('creator_user_id', Auth::user()->id)->first();
        if ($ownedGroup == null) {
            abort(403);
        }
        $ownedGroupMembers = LimoGroupMember::where('limo_group_id', $ownedGroup->id);
        $memberIDs = request('members');
        if ($memberIDs != null) {
            $removedSelf = $removedMember = false;
            $membersModels = $ownedGroupMembers->find($memberIDs);
            $removeMembers = [];
            foreach($membersModels as $member) {
                if ($member == null) {
                    abort(403);
                }
                if ($member->invitee_user_id == Auth::id()) {
                    $removedSelf = true;
                    continue;
                }
                $removeMembers[] = $member;
                $removedMember = true;
            }
            foreach($removeMembers as $member) {
                $member->delete();
            }
            if ($removedSelf && $removedMember) {
                Toast::success('You have removed the selected members successfully, but you can\'t remove yourself');
            } else if ($removedMember) {
                Toast::success('You have removed the selected members successfully');
            } else {
                Toast::error('You can\'t remove yourself from the group');
            }
            
        } else {
            Toast::error('You have not selected any members to remove');
        }
    }
}

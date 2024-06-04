<?php

namespace App\Orchid\Screens;

use App\Models\User;
use Orchid\Screen\TD;
use App\Models\Student;
use Orchid\Screen\Sight;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use App\Models\BeautyGroup;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Models\VendorPackage;
use App\Models\BeautyGroupBid;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use App\Models\BeautyGroupMember;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\SimpleMDE;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Notifications\GroupInvitation;
use Orchid\Screen\Actions\ModalToggle;
use App\Notifications\GeneralNotification;
use Orchid\Screen\Actions\DropDown;

class ViewBeautyGroupScreen extends Screen
{
    public $beauty_group;
    public $owner = false;
    public $owned_beauty_group;
    public $beauty_group_members;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $current_beauty_group =  BeautyGroup::whereNot('creator_user_id', Auth::id())->where('id', BeautyGroupMember::where('invitee_user_id', Auth::id())->where('status', 1)->get('beauty_group_id')->value('beauty_group_id'))->first();
        $owned_beauty_group = BeautyGroup::where('creator_user_id', Auth::user()->id)->first();

        return [
            'owned_beauty_group' => ($owned_beauty_group != null) ? $owned_beauty_group : null,
            'current_beauty_group' => ($current_beauty_group != null) ? $current_beauty_group : null,
            'current_beauty_group_members' => ($current_beauty_group != null) ? $current_beauty_group->members()->paginate(min(request()->query('pagesize', 10), 100)) 
                                    : (($owned_beauty_group != null) ? $owned_beauty_group->members()->paginate(min(request()->query('pagesize', 10), 100))
                                    : []),
            'beauty_group_invitations' => BeautyGroupMember::where('invitee_user_id', Auth::user()->id)->where('status', 0)->paginate(min(request()->query('pagesize', 10), 100)),
            'beautyBids' => BeautyGroupBid::where('beauty_group_id', ($owned_beauty_group != null) ? $owned_beauty_group->id : 0)->where('status', 0)->paginate(min(request()->query('pagesize', 10), 100)),
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
        return 'Beauty Groups';
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
                    Link::make('Create a New Beauty Group')
                        ->icon('plus')
                        ->route('platform.beauty-groups.create'),
                    
                    Button::make('Leave Beauty Group')
                        ->icon('logout')
                        ->method('leaveBeautyGroup', 
                            ['current_beauty_group' => ($this->query()['current_beauty_group'] != null) ? $this->query()['current_beauty_group']->id : null,
                            'owned_beauty_group' => $this->query()['owned_beauty_group'] != null ? $this->query()['owned_beauty_group']->id : null]
                        )
                        ->confirm('WARNING: If you are the owner of the beauty group, the entire group will be deleted. Are you sure you want to leave this beauty group?'),

                    ModalToggle::make('Invite Members')
                        ->modal('inviteMembersModal')
                        ->method('inviteMembers')
                        ->icon('user-follow')
                        ->canSee($this->owned_beauty_group != null),
                    
                    Button::make('Remove Selected Members')
                        ->icon('unfollow')
                        ->method('removeSelectedMembers')
                        ->confirm('Are you sure you want to remove the selected members?')
                        ->canSee($this->owned_beauty_group != null),
                    
                    Button::make('Delete Selected Invitations')
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
        if($this->query()['owned_beauty_group'] != null){
            $this->beauty_group = 'owned_beauty_group';
            $this->owner = true;
        }elseif($this->query()['current_beauty_group'] != null){
            $this->beauty_group = 'current_beauty_group';
        }else{
            $this->beauty_group = 'default';
        }

        return [

          Layout::modal('inviteMembersModal', [
                Layout::rows([

                    ($this->owned_beauty_group != null && $this->owned_beauty_group->creator_user_id == Auth::user()->id) ?

                    //make a Select class only including users who are not already in the beauty group and are students at their school
                    Select::make('invitee_user_ids')
                        ->title('Invite Members')
                        ->placeholder('Select a user')
                        ->help('Select a student to invite to the beauty group.')
                        ->required()
                        ->fromQuery(Student::where('school_id', Auth::user()->student->school_id)->whereNot('user_id', Auth::user()->id)->whereNotIn('user_id', BeautyGroupMember::where('beauty_group_id', $this->owned_beauty_group->id)->get('invitee_user_id')), 'email', 'user_id')
                        ->multiple()
                        ->popover('If you do not see a student you would like to invite, please enter their email and add them.')
                        ->allowAdd() : Input::make('unauthorized', 'You are not authorized to invite members to this beauty group.'),
                ]),
          ])->title('Invite Members')
            ->applyButton('Invite'),

          Layout::tabs([
                'Beauty Group Info' => [
                    Layout::legend($this->beauty_group, [
                        Sight::make('creator_user_id', 'Owner')->render(function (BeautyGroup $beautyGroup = null) {

                            if($beautyGroup == null){
                                return '';
                            }elseif($beautyGroup->owner->user_id == Auth::user()->id){
                                return 'You';
                            }else{
                                return $beautyGroup->owner->firstname . ' ' . $beautyGroup->owner->lastname;
                            }
                        }),
                        Sight::make('name', 'Name')->render(function(BeautyGroup $beautyGroup = null){
                            if($beautyGroup == null){
                                return '';
                            }else{
                                return $beautyGroup->name;
                            }
                        }),
                        Sight::make('capacity', 'Capacity')->render(function(BeautyGroup $beautyGroup = null){
                            if($beautyGroup == null){
                                return '';
                            }else{
                                return $beautyGroup->capacity;
                            }
                        }),
                        Sight::make('date', 'Date')->render(function(BeautyGroup $beautyGroup = null){
                            if($beautyGroup == null){
                                return '';
                            }else{
                                return $beautyGroup->date;
                            }
                        }),
                        Sight::make('pickup_location', 'Pickup Location')->render(function(BeautyGroup $beautyGroup = null){
                            if($beautyGroup == null){
                                return '';
                            }else{
                                return $beautyGroup->pickup_location;
                            }
                        }),
                        Sight::make('dropoff_location', 'Dropoff Location')->render(function(BeautyGroup $beautyGroup = null){
                            if($beautyGroup == null){
                                return '';
                            }else{
                                return $beautyGroup->dropoff_location;
                            }
                        }),
                        Sight::make('depart_time', 'Depart Time')->render(function(BeautyGroup $beautyGroup = null){
                            if($beautyGroup == null){
                                return '';
                            }else{
                                return $beautyGroup->depart_time;
                            }
                        }),
                        Sight::make('dropoff_time', 'Dropoff Time')->render(function(BeautyGroup $beautyGroup = null){
                            if($beautyGroup == null){
                                return '';
                            }else{
                                return $beautyGroup->dropoff_time;
                            }
                        }),
                        Sight::make('notes', 'Notes')->render(function(BeautyGroup $beautyGroup = null){
                            if($beautyGroup == null){
                                return '';
                            }else{
                                return $beautyGroup->notes;
                            }
                        }),
                        Sight::make('', 'Actions')->render(function(BeautyGroup $beautyGroup = null){
                            if($beautyGroup == null || $beautyGroup->owner->user_id != Auth::user()->id){
                                return '';
                            }else{
                                return 
                                    Button::make('Edit')
                                    ->icon('pencil')
                                    ->method('redirect', ['beautyGroup_id' => $beautyGroup->id])
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

                                foreach($this->query()['current_beauty_group_members'] as $member){
                                    $members[$member->user->email] = $member->user->firstname . ' '  . $member->user->lastname;
                                }

                                return $members;
                            }),
                            
                        SimpleMDE::make('content')
                            ->title('Content')
                            ->toolbar(["text", "color", "header", "list", "format", "align", "link", ])
                            ->placeholder('Insert text here ...')
                            ->help('Add the content for the message that you would like to send.'),
                    ])
    
                ],
                'Members in Group' => [
                    Layout::table('current_beauty_group_members', [

                        TD::make()
                            ->render(function (BeautyGroupMember $student){
                                return ($student->beautyGroup->creator_user_id == Auth::user()->id) ? 
                                 CheckBox::make('members[]')
                                    ->value($student->id)
                                    ->checked(false) : ''; 
                            })->width('5%'),

                        TD::make('firstname', 'First Name')
                            ->render(function (BeautyGroupMember $student) {
                                return e($student->user->firstname);
                            })->width('10%'),
                        TD::make('lastname', 'Last Name')
                            ->render(function (BeautyGroupMember $student) {
                                return e($student->user->lastname);
                            })->width('10%'),
                        TD::make('email', 'Email')->width('105')
                            ->render(function (BeautyGroupMember $student) {
                                return e($student->user->email);
                            })->width('20%'),
                            
                        TD::make('phonenumber', 'Phone Number')
                            ->render(function (BeautyGroupMember $student) {
                                return e($student->user->phonenumber);
                            }),

                        TD::make('status', 'Invite Status')
                            ->render(function (BeautyGroupMember $student) {
                                return 
                                    ($student->status == 0) ? '<i class="text-warning">●</i> Pending' 
                                    : (($student->status == 1) ? '<i class="text-success">●</i> Accepted' 
                                    : '<i class="text-danger">●</i> Rejected');
                                        }),

                        TD::make('paid', 'Payment Status')
                            ->render(function (BeautyGroupMember $student) {
                                return (($student->beautyGroup->creator_user_id != Auth::id()) ? 'Confidential' : (($student->paid == 0) ? '<i class="text-danger">●</i> Unpaid' : '<i class="text-success">●</i> Paid')) ;
                            }),

                        TD::make('created_at', 'Invited At')
                            ->render(function (BeautyGroupMember $student) {
                                return e($student->created_at);
                            }),                        

                    ])
                ],
                'Received Beauty Group Invitations' => [
                    Layout::table('beauty_group_invitations', [

                        TD::make('actions', 'Actions')
                            ->render(function (BeautyGroupMember $student){
                                return CheckBox::make('invitations[]')
                                    ->value($student->id)
                                    ->checked(false);
                            }),

                        TD::make()
                            ->align(TD::ALIGN_LEFT)
                            ->width('100px')
                            ->render(function(BeautyGroupMember $student_beauty_member){
                                return 
                                Button::make('Accept')
                                    ->confirm('WARNING: Joining a beauty group will remove you from your current beauty group if you are in one. And if you own a beauty group, it will delete it and all the memebers in it. Are you sure you want to join this beauty group?')
                                    ->method('updateInvitation', ['beauty_group_member_id' => $student_beauty_member->id, 'choice' => 1])
                                    ->icon('check')->type(Color::SUCCESS())->class('btn btn-success btn-rounded'); 
                                }), 

                        TD::make()
                            ->align(TD::ALIGN_LEFT)
                            ->width('100px')
                            ->render(function(BeautyGroupMember $student_beauty_member){
                                return Button::make('Reject')->method('updateInvitation', ['beauty_group_member_id' => $student_beauty_member->id, 'choice' => 2])->icon('close')->type(Color::DANGER()); 
                                }),

                        TD::make('owner', 'Owner')
                            ->render(function (BeautyGroupMember $student) {
                                return e(BeautyGroup::find($student->beauty_group_id)->owner->firstname . ' ' . BeautyGroup::find($student->beauty_group_id)->owner->lastname);
                            }),
                        
                        TD::make('capacity', 'Capacity')
                            ->render(function (BeautyGroupMember $student) {
                                return e(BeautyGroup::find($student->beauty_group_id)->capacity);
                            }),
                        
                        TD::make('date', 'Date')
                            ->render(function (BeautyGroupMember $student) {
                                return e(BeautyGroup::find($student->beauty_group_id)->date);
                            }),
                        
                        TD::make('pickup_location', 'Pickup Location')
                            ->render(function (BeautyGroupMember $student) {
                                return e(BeautyGroup::find($student->beauty_group_id)->pickup_location);
                            }),
                        
                        TD::make('dropoff_location', 'Dropoff Location')
                            ->render(function (BeautyGroupMember $student) {
                                return e(BeautyGroup::find($student->beauty_group_id)->dropoff_location);
                            }),
                        
                        TD::make('depart_time', 'Depart Time')
                            ->render(function (BeautyGroupMember $student) {
                                return e(BeautyGroup::find($student->beauty_group_id)->depart_time);
                            }),
                        
                        TD::make('dropoff_time', 'Dropoff Time')
                            ->render(function (BeautyGroupMember $student) {
                                return e(BeautyGroup::find($student->beauty_group_id)->dropoff_time);
                            }),

                        TD::make('notes', 'Notes')
                            ->render(function (BeautyGroupMember $student) {
                                return e(BeautyGroup::find($student->beauty_group_id)->notes);
                            }),
                    ])
                ],

                'Pending Bids' => ($this->owner) ?
                    
                    Layout::table('beautyBids', [

                            TD::make()
                                ->align(TD::ALIGN_LEFT)
                                ->width('100px')
                                ->render(function(BeautyGroupBid $bid){
                                    return Button::make('Accept')->method('updateBid', ['bid_id' => $bid->id, 'choice' => 1, 'beauty_group_id' => $bid->beautyGroup->id])->icon('check')->type(Color::SUCCESS()); 
                                    }), 

                            TD::make()
                                ->align(TD::ALIGN_LEFT)
                                ->width('100px')
                                ->render(function(BeautyGroupBid $bid){
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
            $bid = BeautyGroupBid::find(request('bid_id'));
            $new_vendor = User::find($bid->user_id);
            $bid->status = request('choice');

            if(request('choice') == 1){
                $old_beauty_group_bid = BeautyGroupBid::where('beauty_group_id', request('beauty_group_id'))->where('status', 1)->first();
                if($old_beauty_group_bid != null){
                    $old_beauty_group_bid->status = 0;
                    $old_beauty_group_bid->save();
                    $old_vendor = User::find($old_beauty_group_bid->user_id);
                    $old_vendor->notify(new GeneralNotification([
                        'title' => 'Beauty Group Bid Changed',
                        'message' => 'Your bid for the ' . $old_beauty_group_bid->beautyGroup->name . ' beauty group has been changed. Please contact the beauty group owner for more information.',
                        'action' => '/admin/bids/history',
                    ]));

                    $new_vendor->notify(new GeneralNotification([
                        'title' => 'Beauty Group Bid Accepted',
                        'message' => 'Your bid for the ' . $bid->beautyGroup->name . ' beauty group has been accepted. Please contact the beauty group owner for more information.',
                        'action' => '/admin/bids/history',
                    ]));
                }
            } else{

                $new_vendor->notify(new GeneralNotification([
                    'title' => 'Beauty Group Bid Rejected',
                    'message' => 'Your bid for the ' . $bid->beautyGroup->name . ' beauty group has been rejected. Please contact the beauty group owner for more information.',
                    'action' => '/admin/bids/history',
                ]));

            }

            $bid->save();
            Toast::success('Bid updated successfully!');
            return redirect()->route('platform.beauty-groups');
        } catch (\Exception $e) {
            Toast::error($e->getMessage());
            return redirect()->route('platform.beauty-groups');
        }
    }

    public function deleteSelectedInvitations(){
        $invitations = request('invitations');

        if($invitations != null){
            foreach($invitations as $invitation){
                BeautyGroupMember::find($invitation)->delete();
            }
            Toast::success('You have deleted the selected invitations successfully');
        } else{
            Toast::error('You have not selected any invitations to delete');
        }
    }

    public function leaveBeautyGroup(){

        try{
            if(request('current_beauty_group') != null){
                
                BeautyGroupMember::where('invitee_user_id', Auth::id())->where('status', 1)->where('beauty_group_id', request('current_beauty_group'))->delete();
                $current_beauty_group = BeautyGroup::find(request('current_beauty_group'));
                $current_beauty_group->increment('capacity');
                $current_beauty_group->save();
                Toast::success('You have left the beauty group successfully');

            } elseif(request('owned_beauty_group') != null){

                $owned_beauty_group = BeautyGroup::find(request('owned_beauty_group'));
                $owned_beauty_group->delete();
                Toast::success('You have deleted the beauty group successfully');
            } else{
                Toast::error('You are not in a beauty group');
            }

            return redirect()->route('platform.beauty-groups');
        } catch(\Exception $e){
            Toast::error('There was an error leaving the beauty group');
            return redirect()->route('platform.beauty-groups');
        }

    }

    public function redirect(){
        return redirect()->route('platform.beauty-groups.edit', request('beautyGroup_id'));
    }

    public function updateInvitation(){
        $beauty_group_member = BeautyGroupMember::find(request('beauty_group_member_id'));
        $beauty_group_member->status = request('choice');

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

        if(request('choice') == 1){
            //update the beauty group capacity
            $beauty_group = BeautyGroup::find($beauty_group_member->beautyGroup->id);
            $beauty_group->decrement('capacity');
            $beauty_group->save();
        }

        $beauty_group_member->save();
        Toast::success('Invitation updated successfully!');
        return redirect()->route('platform.beauty-groups');
    }

    public function inviteMembers(){
        try{
                $invitee_user_ids = request('invitee_user_ids');
                $beauty_group = BeautyGroup::where('creator_user_id', Auth::user()->id)->first();
                
                foreach($invitee_user_ids as $invitee_user_id){

                        if(is_numeric($invitee_user_id)){

                            $user = User::find($invitee_user_id);
                            $user->notify(new GroupInvitation([
                                'title' => 'You have been invited to join a beauty group!',
                                'message' => 'You have been invited to join a beauty group by ' . Auth::user()->firstname . ' ' . Auth::user()->lastname . '. Please check your beauty group invitations page to accept or reject the invitation.',
                                'action' => '/admin/beauty-groups/'
                            ]));

                            $current_beauty_group = new BeautyGroupMember();
                            $current_beauty_group->beauty_group_id = $beauty_group->id;
                            $current_beauty_group->invitee_user_id = $invitee_user_id;
                            $current_beauty_group->save();
                            
                        } else{
                            //send email to the email entered by user
                            $data = [
                                'inviter_name' => Auth::user()->firstname . ' ' . Auth::user()->lastname,
                                'inviter_school' => Auth::user()->student->school,
                                'beauty_group_name' => $beauty_group->name,
                            ];

                            Mail::send(
                                'emails.beautyGroup', $data, function (Message $message) use ($invitee_user_id) {
                                    $message->subject('You have been invited to join a beauty group!');
                                    $message->to($invitee_user_id);
                                }
                            );
                        }
                    }

                Toast::success('You have successfully invited ' . count($invitee_user_ids) . ' member(s) to your beauty group!');
            } catch(\Exception $e){
                Toast::error('Something went wrong. Error Code:' . $e->getMessage());
            } 
    }

    public function removeSelectedMembers() {
        $ownedGroup = BeautyGroup::where('creator_user_id', Auth::user()->id)->first();
        if ($ownedGroup == null) {
            abort(403);
        }
        $ownedGroupMembers = BeautyGroupMember::where('beauty_group_id', $ownedGroup->id);
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

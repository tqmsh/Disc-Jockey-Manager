<?php

namespace App\Orchid\Screens;

use App\Models\User;
use App\Models\Events;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use App\Models\StudentBids;
use App\Models\LimoGroupBid;
use App\Models\BeautyGroupBid;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Notifications\GeneralNotification;
use App\Orchid\Layouts\ViewLimoBidsLayout;
use App\Orchid\Layouts\ViewBeautyBidsLayout;
use App\Orchid\Layouts\ViewStudentBidsLayout;
use App\Orchid\Layouts\ViewBeautyGroupBidLayout;
use App\Orchid\Layouts\ViewPendingStudentBidsLayout;
use App\Models\Vendors;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;

class ViewStudentBidScreen extends Screen
{
    public $event;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'pendingBids' => StudentBids::filter(request(['category_id']))->where('student_user_id', Auth::user()->id)->where('status', 0)->latest()->paginate(10),
            'previousBids' => StudentBids::filter(request(['category_id']))->where('student_user_id', Auth::user()->id)->whereNot('status', 0)->orderBy('status')->latest()->paginate(10),
            'previousLimoBids' => LimoGroupBid::filter(request(['category_id']))->where('limo_group_id', Auth::user()->limoGroup ? Auth::user()->limoGroup->id : 0)->whereNot('status', 0)->orderBy('status')
            ->latest()->paginate(10),
            'previousBeautyBids' => BeautyGroupBid::filter(request(['category_id']))->where('beauty_group_id', Auth::user()->beautyGroup ? Auth::user()->beautyGroup->id : 0)->whereNot('status', 0)->orderBy('status')->latest()->paginate(10),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Bids Placed on You';
    }

    public function description(): ?string
    {
        return 'Interested Categories: ' . (Auth::user()->student->getInterestedCategoriesNames() ?? 'None');
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Suggest Category')
                ->modal('suggestCategoryModal')
                ->method('suggestCategory')
                ->icon('plus'),
            ModalToggle::make('Edit Interested Categories')
                ->modal('editInterestedCategoriesModal')
                ->method('updateInterestedCategories')
                ->icon('pencil'),
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
            Layout::modal('editInterestedCategoriesModal', [
                Layout::rows([
                    Select::make('interested_vendor_categories')
                        ->title('Interested Vendor Categories')
                        ->fromModel(Categories::class, 'name')
                        ->multiple()
                        ->help('Vendors from this category will be able to place bids on the event.')
                        ->value(Auth::user()->student->interested_vendor_categories),
                ])
            ])->title('Edit Interested Categories')
                ->applyButton('Save'),
            
            Layout::modal('suggestCategoryModal', [
                Layout::rows([
                    Input::make('category_name')
                        ->title('Category Name')
                        ->placeholder('Enter the name of the category')
                        ->help('Suggest a category to be reviewed and approved by an admin')
                        ->required(),
                ])
            ])->title('Suggest Category')
                ->applyButton('Suggest'),

            Layout::rows([
                Group::make([
                    
                    Select::make('category_id')
                        ->help('Type in box to search')
                        ->empty('Filter Category')
                        ->fromModel(Categories::query(), 'name'),

                    Button::make('Filter')
                        ->icon('filter')
                        ->method('filter')
                        ->type(Color::DEFAULT()),
                ]),
                
            ]),

            Layout::tabs([
                'Pending Bids' => [
                    ViewPendingStudentBidsLayout::class
                ],
                'Previous Bids' => [
                    ViewStudentBidsLayout::class
                ],
                'Limo Group Bids' => [
                    ViewLimoBidsLayout::class
                ],
                'Beauty Group Bids' => [
                    ViewBeautyBidsLayout::class
                ],


            ]),
        ];
    }
    
    public function filter()
    {
        return redirect()->route('platform.studentBids.list', ['category_id' => request('category_id')]);
    }

    public function redirect($bid){
        return redirect()-> route('platform.studentBids.edit', $bid);
    }

    public function updateBid()
    {
        try {
            $bid = StudentBids::find(request('bid_id'));
            $vendor = User::find($bid->user_id);
            $vendor_1 = Vendors::where('user_id', $bid->user_id)->first();
            $bid->status = request('choice');
            $bid->save();

            $adPrice = 50;


            if(request('choice') == 1){
                $vendor_1->decrement('credits', $adPrice);

                $vendor->notify(new GeneralNotification([
                    'title' => 'Student Bid Accepted!',
                    'message' => 'Your bid for ' . Auth::user()->name . ' has been accepted!',
                    'action' => '/admin/bids/history'
                ]));
            } else{

                $vendor->notify(new GeneralNotification([
                    'title' => 'Student Bid Declined!',
                    'message' => 'Your bid for ' . Auth::user()->name . ' has been declined!',
                    'action' => '/admin/bids/history'
                ]));
            }

            Toast::success('Bid updated successfully!');
            return redirect()->route('platform.studentBids.list');
        } catch (\Exception $e) {
            Toast::error('Something went wrong. Error: ' . $e->getMessage());
        }
    }

    public function updateInterestedCategories()
    {
        $validator = Validator::make(request(['interested_vendor_categories',]), [
            'interested_vendor_categories' => 'nullable|array',
            'interested_vendor_categories.*' => Rule::in(Categories::all()->pluck('id')),
        ],
        $messages = [
            'interested_vendor_categories.*.in' => 'The interested categories are invalid.'
        ]);
        $validated = $validator->validated();
        $validated['interested_vendor_categories'] = $validated['interested_vendor_categories'] ?? null;
        Auth::user()->student->update($validated);
        Toast::success('Interested categories updated successfully');
    }

    public function suggestCategory()
    {
        $validator = Validator::make(request()->all(), [
            'category_name' => 'required|max:255|unique:categories,name',
        ]);
        Categories::create(['name' => $validator->validated()['category_name']]);
        Toast::success('Category suggested succesfully');
    }
}

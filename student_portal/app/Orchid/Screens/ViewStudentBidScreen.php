<?php

namespace App\Orchid\Screens;

use App\Models\Events;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Categories;
use App\Models\StudentBids;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewStudentBidsLayout;
use App\Orchid\Layouts\ViewPendingStudentBidsLayout;

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
            'previousBids' => StudentBids::filter(request(['category_id']))->where('student_user_id', Auth::user()->id)->whereNot('status', 0)->orderBy('status')->latest()->paginate(10)
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

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

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
            ]),
        ];
    }
    
    public function filter(Events $event)
    {
        return redirect()->route('platform.studentBids.list', ['category_id' => request('category_id')]);
    }

    public function redirect($bid){
        return redirect()-> route('platform.studentBids.edit', $bid);
    }

    public function updateBid()
    {
        $bid = StudentBids::find(request('bid_id'));
        $bid->status = request('choice');
        $bid->save();
        Toast::success('Bid updated successfully!');
        return redirect()->route('platform.studentBids.list',);
    }
}

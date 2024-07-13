<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Events;
use App\Models\Staffs; // Updated
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\ViewStudentLayout;

class ViewStudentScreen extends Screen // Updated class name
{
    public function query(): iterable
    {
        return [
            'staffs' => Staffs::latest('created_at')
                ->paginate(request()->query('pagesize', 20))
        ];
    }

    public function name(): ?string
    {
        return 'Staffs'; // Updated
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Add New Staff')
                ->icon('plus')
                ->route('platform.student.create'),

            Button::make('Delete Selected Staff')
                ->icon('trash')
                ->method('deleteStaffs')
                ->confirm(__('Are you sure you want to delete the selected staff?')),

            Link::make('Contact Staff')
                ->icon('comment')
                ->route('platform.contact-students'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.student.list'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Button::make('Filter') // Optional
                    ->icon('filter')
                    ->method('filter')
                    ->type(Color::DEFAULT()),
            ]),
            
            // ViewStudentLayout::class // Ensure this points to the correct layout
        ];
    }

    public function deleteStaffs(Request $request) // Updated method name
    {   
        $staffs = $request->get('staffs');
        
        try {
            if (!empty($staffs)) {
                Staff::whereIn('id', $staffs)->delete();
                Toast::success('Selected staff deleted successfully');
            } else {
                Toast::warning('Please select staff in order to delete them');
            }
        } catch (Exception $e) {
            Alert::error('There was an error trying to delete the selected staff. Error Message: ' . $e->getMessage());
        }
    }
}

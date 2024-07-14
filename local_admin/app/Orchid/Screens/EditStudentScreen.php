<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Staffs;
use Orchid\Screen\Screen;
use App\Models\Localadmin;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EditStudentScreen extends Screen
{
    public $staff;
    public function query(Staffs $staff): iterable
    {
        return [
            'staff' => $staff
        ];
    }
    public function name(): ?string
    {
        return 'Edit Staff: ' . $this->staff->first_name . ' ' . $this->staff->last_name;
    }
    public function commandBar(): iterable
    {
        return [
            Button::make('Submit')
                ->icon('check')
                ->method('update'),

            Button::make('Delete Staff')
                ->icon('trash')
                ->method('delete'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.student.list')
        ];
    }
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('first_name')
                    ->title('First Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->staff->first_name),

                Input::make('last_name')
                    ->title('Last Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->staff->last_name),

                Select::make('position')
                    ->title('Position')
                    ->required()
                    ->horizontal()
                    ->options([
                        'DJ' => 'DJ',
                        'MC' => 'MC',
                        'Attendant' => 'Attendant',
                        'Tech' => 'Tech',
                        'Dancer' => 'Dancer',
                        'Roadie' => 'Roadie',
                    ])
                    ->value($this->staff->position),

                Select::make('gender')
                    ->title('Gender')
                    ->required()
                    ->horizontal()
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ])
                    ->value($this->staff->gender),

                Input::make('email')
                    ->title('Email')
                    ->type('email')
                    ->required()
                    ->horizontal()
                    ->value($this->staff->email),

                Input::make('cell')
                    ->title('Phone Number')
                    ->type('text')
                    ->horizontal()
                    ->value($this->staff->cell),

                Input::make('age')
                    ->title('Age')
                    ->type('number')
                    ->required()
                    ->horizontal()
                    ->value($this->staff->age),
            ]),
        ];
    }

    public function update(Staffs $staff, Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('staffs')->ignore($staff->id), // Change to 'staffs'
                ],
                'position' => 'required',
                'gender' => 'required',
                'cell' => 'nullable|max:20',
                'age' => 'nullable|integer',
            ]);
    
            $staffTableFields = $this->getStaffFields($request);
    
            // Update staff record
            $staff->update($staffTableFields);
    
            Toast::success('You have successfully updated: ' . $request->input('first_name') . ' ' . $request->input('last_name') . '.');
    
            return redirect()->route('platform.student.list'); // Adjust the redirect if necessary
    
        } catch (Exception $e) {
            Alert::error('There was an error editing this staff. Error Code: ' . $e->getMessage());
        }
    }

    public function delete(Staffs $staff)
    {
        try {
            User::where('id', $staff->user_id)->delete();
            $staff->delete();

            Toast::info('You have successfully deleted the staff.');

            return redirect()->route('platform.staff.list');

        } catch (Exception $e) {
            Alert::error('There was an error deleting this staff. Error Code: ' . $e->getMessage());
        }
    } 
    private function getStaffFields($request)
    {
        return [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'position' => $request->input('position'),
            'gender' => $request->input('gender'),
            'email' => $request->input('email'),
            'cell' => $request->input('cell'),
            'age' => $request->input('age'),
        ];
    }

    private function getUserFields($request)
    {
        return [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'cell' => $request->input('cell'),
        ];
    }
}

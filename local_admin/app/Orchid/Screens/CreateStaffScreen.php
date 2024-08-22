<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\User;
use App\Models\Staffs;
use App\Models\Student;
use App\Models\RoleUsers;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Password;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Facades\Dashboard;
use Orchid\Screen\Actions\ModalToggle;




class CreateStaffScreen extends Screen
{
    public $requiredFields = ['firstname', 'lastname', 'position', 'gender', 'email'];
    public $staff;

    public function query(Request $request): iterable
    {
        // Initialize staff attributes
        $this->staff = new Staffs();

        return [
            'staff' => $this->staff
        ];
    }

    public function name(): ?string
    {
        return 'Add a New Staff';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.student.list')
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('firstname')
                    ->title('First Name')
                    ->required()
                    ->value($this->staff->first_name),

                Input::make('lastname')
                    ->title('Last Name')
                    ->required()
                    ->value($this->staff->last_name),

                Select::make('position')
                    ->title('Position')
                    ->required()
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
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ])
                    ->value($this->staff->gender),

                Input::make('email')
                    ->title('Email')
                    ->required()
                    ->value($this->staff->email),

                Input::make('cell')
                    ->title('Phone Number')
                    ->value($this->staff->cell),

                Input::make('age')
                    ->title('Age')
                    ->type('number')
                    ->value($this->staff->age),

                Button::make('Add Staff')
                    ->icon('plus')
                    ->type(Color::PRIMARY())
                    ->method('createStaff'),
            ]),
        ];
    }

    public function createStaff(Request $request)
    {
        try {
            $request->validate([
                'firstname' => 'required|max:255',
                'lastname' => 'required|max:255',
                'position' => 'required|in:DJ,MC,Attendant,Tech,Dancer,Roadie',
                'gender' => 'required|in:Male,Female',
                'email' => 'required|email|max:255|unique:staffs',
                'cell' => 'nullable|max:20',
                'age' => 'nullable|integer',
            ]);

            // Create the staff record
            Staffs::create([
                'first_name' => $request->input('firstname'),
                'last_name' => $request->input('lastname'),
                'position' => $request->input('position'),
                'gender' => $request->input('gender'),
                'email' => $request->input('email'),
                'cell' => $request->input('cell'),
                'age' => $request->input('age'),
            ]);

            Toast::success('Staff Added Successfully');

            return redirect()->route('platform.student.list');

        } catch (Exception $e) {
            Alert::error('There was an error creating this staff. Error Code: ' . $e->getMessage());
            return redirect()->route('platform.student.create', request(['firstname', 'lastname', 'position', 'gender', 'email', 'cell', 'age']));
        }
    }
}
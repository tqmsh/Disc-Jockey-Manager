<?php

namespace App\Orchid\Screens;

use App\Models\Localadmin;
use App\Models\LoginAs;
use App\Models\Region;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Orchid\Layouts\ViewLoginAsLayout;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ViewLoginAsScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'users' => $this->returnFilteredUsers()->paginate(10)
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Login As';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
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
                    Select::make('login_as_role')
                        ->title('Role')
                        ->options([
                            2 => 'Local Admin',
                            3 => 'Student',
                            4 => 'Vendor'
                        ])
                        ->empty('Select a role...'),

                    Select::make('login_as_region')
                        ->title('Region')
                        ->fromModel(Region::class, 'name')
                        ->empty('Select a region...'),
                    
                    Select::make('login_as_name')
                        ->title('User name')
                        ->fromQuery(User::whereNot('role', 1), 'name', 'name')
                        ->empty('Type a name...'),
                        
                    Select::make('login_as_email')
                        ->title('Email')
                        ->fromQuery(User::whereNot('role', 1), 'email', 'email')
                        ->empty('Type an email...')
                ]),
                
                
                Button::make('Filter')
                    ->method('filter')
                    ->icon('filter')
                    ->type(Color::DEFAULT())
            ]),

            ViewLoginAsLayout::class
        ];
    }

    public function filter() {
        if($this->emptyFilters()) {
            Toast::error('Please select at least one of the filters.');
            return to_route('platform.login-as.view');
        }

        return to_route('platform.login-as.view', [
            'login_as_role' => request()->input('login_as_role'),
            'login_as_region' => request()->input('login_as_region'),
            'login_as_name' => request()->input('login_as_name'),
            'login_as_email' => request()->input('login_as_email')
        ]);
    }

    public function loginAsUser($user_id, $portal) {
        // Create new LoginAs row
        $la_session = LoginAs::create([
            'user_id' => $user_id,
            'portal' => $portal
        ]);

        return to_route('platform.login-as.generated', ['loginAs' => $la_session->id]);
    }

    private function returnFilteredUsers() {
        // Exclude super admins from base query
        $query = DB::table('users')->whereNot('role', 1);

        if(request('login_as_email') !== null) {
            return $query->where('email', request('login_as_email'));
        }

        if(request('login_as_name') !== null) {
           $query->where('name', 'like', '%' . request('login_as_name') . '%');
        }

        if(request('login_as_region') == null && request('login_as_role') !== null) {
            // Only role inputted.
            $query->where('role', request('login_as_role'));
        } else if(request('login_as_role') !== null && request('login_as_region') !== null) {
            // Only role and region inputted.
            match((int)request('login_as_role')) {
                2 => $query->whereIn('id', Localadmin::whereIn('school_id', School::where('region_id', request('login_as_region'))->pluck('id'))->pluck('user_id')),
                3 => $query->whereIn('id', Student::whereIn('school_id', School::where('region_id', request('login_as_region'))->pluck('id'))->pluck('user_id')),
                4 => $query->where('role', 4)
            };
        } else if(request('login_as_role') == null && request('login_as_region') !== null) {
            // Only region inputted.
            $schoolsInRegion = School::where('region_id', request('login_as_region'));
            
            // Get local admins and students in region
            $localAdmins = Localadmin::whereIn('school_id', $schoolsInRegion->pluck('id'))->get();
            $students = Student::whereIn('school_id', $schoolsInRegion->pluck('id'))->get();

            $users = $localAdmins->merge($students)->pluck('user_id');
            
            $query->whereIn('id', $users);
        }

        return $query;
    }
    
    private function emptyFilters() : bool{
        return request()->input('login_as_role') == null && request()->input('login_as_region') == null && request()->input('login_as_name') == null && request()->input('login_as_email') == null;
    }
}

<?php

namespace App\Orchid\Screens;

use App\Models\Localadmin;
use App\Models\LoginAs;
use App\Models\Region;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Orchid\Layouts\ViewLoginAsLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

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
            'users' => !is_null(request('login_as_role')) ? $this->returnFilteredUsers()->paginate(10) : []
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
                        ->title('Role (Required)')
                        ->options([
                            2 => 'Local Admin',
                            3 => 'Student',
                            4 => 'Vendor'
                        ])
                        ->empty('Select a role...'),

                    Select::make('login_as_region')
                        ->title('Region (Optional. Only for Local Admin and Student)')
                        ->fromModel(Region::class, 'name')
                        ->empty('Select a region...')
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
        return to_route('platform.login-as.view', [
            'login_as_role' => request('login_as_role'),
            'login_as_region' => request('login_as_region')
        ]);
    }

    public function loginAsUser($user_id, $portal) {
        // Create new LoginAs row
        $la_session = LoginAs::create([
            'user_id' => $user_id,
            'portal' => $portal
        ]);

        // Redirect to other portal website
        $baseURL = $la_session->portalToTarget();

        dd("http://{$baseURL}/login-as/{$la_session->la_key}");
        //return redirect()->away("https://{$baseURL}/login-as/{$la_session->la_key}");
    }

    private function returnFilteredUsers() {
        // Just return users with roles if no region
        if(request('login_as_region') == null){
            return User::where('role', request('login_as_role'));
        }

        return match((int)request('login_as_role')) {
            2 => User::whereIn('id', Localadmin::whereIn('school_id', School::where('region_id', request('login_as_region'))->pluck('id'))->pluck('user_id')),
            3 => User::whereIn('id', Student::whereIn('school_id', School::where('region_id', request('login_as_region'))->pluck('id'))->pluck('user_id')),
            4 => User::where('role', 4)
        };
    }
}

<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Venues; // Updated model 
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

class CreateVenueScreen extends Screen
{
    public $requiredFields = ['name', 'address', 'city', 'state_province', 'country', 'zip_postal', 'phone', 'email'];
    public $venue;

    public function query(Request $request): iterable
    {
        // Initialize venue attributes
        $this->venue = new Venues();

        return [
            'venue' => $this->venue
        ];
    }

    public function name(): ?string
    {
        return 'Add a New Venue';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.venue.list')
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('name')
                    ->title('Venue Name')
                    ->required()
                    ->value($this->venue->name),  // Update here
    
                Input::make('address')
                    ->title('Address')
                    ->required()
                    ->value($this->venue->address),
    
                Input::make('city')
                    ->title('City')
                    ->required()
                    ->value($this->venue->city),
    
                Input::make('state_province')
                    ->title('State/Province')
                    ->required()
                    ->value($this->venue->state_province),
    
                Input::make('country')
                    ->title('Country')
                    ->required()
                    ->value($this->venue->country),
    
                Input::make('zip_postal')
                    ->title('ZIP/Postal Code')
                    ->required()
                    ->value($this->venue->zip_postal),
    
                Input::make('phone')
                    ->title('Phone Number')
                    ->value($this->venue->phone),  // Update here
    
                Input::make('email')
                    ->title('Email')
                    ->required()
                    ->value($this->venue->email),
    
                Button::make('Add Venue')
                    ->icon('plus')
                    ->type(Color::PRIMARY())
                    ->method('createVenue'),
            ]),
        ];
    }
    

    public function createVenue(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:255',
                'address' => 'required|max:255',
                'city' => 'required|max:255',
                'state_province' => 'required|max:255',
                'country' => 'required|max:255',
                'zip_postal' => 'required|max:255',
                'phone' => 'nullable|max:20',
                'email' => 'required|email|max:255|unique:venues',
            ]);

            // Create the venue record
            Venues::create([
                'name' => $request->input('name'),  // Update here
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'state_province' => $request->input('state_province'),
                'country' => $request->input('country'),
                'zip_postal' => $request->input('zip_postal'),
                'phone' => $request->input('phone'),  // Update here
                'email' => $request->input('email'),
            ]);

            Toast::success('Venue Added Successfully');

            return redirect()->route('platform.venue.list');

        } catch (Exception $e) {
            Alert::error('There was an error creating this venue. Error Code: ' . $e->getMessage());
            return redirect()->route('platform.venue.create', request(['name', 'address', 'city', 'state_province', 'country', 'zip_postal', 'phone', 'email']));
        }
    }

}



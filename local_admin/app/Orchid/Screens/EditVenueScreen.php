<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Venues; // Updated to Venues
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Validation\Rule;

class EditVenueScreen extends Screen
{
    public $venue;

    public function query(Venues $venue): iterable
    {
        return [
            'venue' => $venue
        ];
    }

    public function name(): ?string
    {
        return 'Edit Venue: ' . $this->venue->name;
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Submit')
                ->icon('check')
                ->method('update'),

            Button::make('Delete Venue')
                ->icon('trash')
                ->method('delete'),

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
                    ->title('Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->value($this->venue->name),

                Input::make('address')
                    ->title('Address')
                    ->type('text')
                    ->horizontal()
                    ->value($this->venue->address),

                Input::make('city')
                    ->title('City')
                    ->type('text')
                    ->horizontal()
                    ->value($this->venue->city),

                Input::make('state_province')
                    ->title('State/Province')
                    ->type('text')
                    ->horizontal()
                    ->value($this->venue->state_province),

                Input::make('country')
                    ->title('Country')
                    ->type('text')
                    ->horizontal()
                    ->value($this->venue->country),

                Input::make('zip_postal')
                    ->title('Zip/Postal Code')
                    ->type('text')
                    ->horizontal()
                    ->value($this->venue->zip_postal),

                Input::make('website')
                    ->title('Website')
                    ->type('text')
                    ->horizontal()
                    ->value($this->venue->website),

                Input::make('contact_first_name')
                    ->title('Contact First Name')
                    ->type('text')
                    ->horizontal()
                    ->value($this->venue->contact_first_name),

                Input::make('contact_last_name')
                    ->title('Contact Last Name')
                    ->type('text')
                    ->horizontal()
                    ->value($this->venue->contact_last_name),

                Input::make('email')
                    ->title('Contact Email')
                    ->type('email')
                    ->horizontal()
                    ->value($this->venue->email),

                Input::make('phone')
                    ->title('Contact Phone')
                    ->type('text')
                    ->horizontal()
                    ->value($this->venue->phone),
            ]),
        ];
    }

    public function update(Venues $venue, Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:255',
                'address' => 'nullable|max:255',
                'city' => 'nullable|max:255',
                'state_province' => 'nullable|max:255',
                'country' => 'nullable|max:255',
                'zip_postal' => 'nullable|max:255',
                'website' => 'nullable|max:255',
                'contact_first_name' => 'nullable|max:255',
                'contact_last_name' => 'nullable|max:255',
                'email' => [
                    'nullable',
                    'email',
                    'max:255',
                    Rule::unique('venues')->ignore($venue->id),
                ],
                'phone' => 'nullable|max:255',
            ]);
    
            $venueTableFields = $this->getVenueFields($request);
    
            // Update venue record
            $venue->update($venueTableFields);
    
            Toast::success('You have successfully updated the venue: ' . $request->input('name') . '.');
    
            return redirect()->route('platform.venue.list');
    
        } catch (Exception $e) {
            Alert::error('There was an error editing this venue. Error Code: ' . $e->getMessage());
        }
    }

    public function delete(Venues $venue)
    {
        try {
            $venue->delete();

            Toast::info('You have successfully deleted the venue.');

            return redirect()->route('platform.venue.list');

        } catch (Exception $e) {
            Alert::error('There was an error deleting this venue. Error Code: ' . $e->getMessage());
        }
    } 

    private function getVenueFields($request)
    {
        return [
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'state_province' => $request->input('state_province'),
            'country' => $request->input('country'),
            'zip_postal' => $request->input('zip_postal'),
            'website' => $request->input('website'),
            'contact_first_name' => $request->input('contact_first_name'),
            'contact_last_name' => $request->input('contact_last_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ];
    }
}

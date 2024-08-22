<?php

namespace App\Orchid\Screens;

use Exception;
use App\Models\Venues; // Updated model name
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
use App\Orchid\Layouts\ViewVenueLayout; // Updated layout class

class ViewVenueScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'venues' => Venues::latest('created_at')
                ->paginate(request()->query('pagesize', 20))
        ];
    }

    public function name(): ?string
    {
        return 'Venues'; // Updated name
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Add New Venue') // Updated button label
                ->icon('plus')
                ->route('platform.venue.create'), // Updated route

            Button::make('Delete Selected Venues') // Updated button label
                ->icon('trash')
                ->method('deleteVenues') // Updated method name
                ->confirm(__('Are you sure you want to delete the selected venues?')), // Updated confirmation message

            Link::make('Contact Venue') // Updated button label (if applicable)
                ->icon('comment')
                ->route('platform.contact-students'), // Updated route

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.venue.list'), // Updated route
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
            
            ViewVenueLayout::class // Ensure this points to the correct layout
        ];
    }

    public function deleteVenues(Request $request) // Updated method name
    {   
        $venues = $request->get('venues'); // Updated variable name
        
        try {
            if (!empty($venues)) {
                Venues::whereIn('id', $venues)->delete(); // Updated model name
                Toast::success('Selected venues deleted successfully'); // Updated message
            } else {
                Toast::warning('Please select venues in order to delete them'); // Updated message
            }
        } catch (Exception $e) {
            Alert::error('There was an error trying to delete the selected venues. Error Message: ' . $e->getMessage()); // Updated message
        }
    }
}

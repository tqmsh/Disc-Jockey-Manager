<?php

namespace App\Orchid\Screens;

use App\Models\Dress;
use App\Models\Wishlist;
use App\Orchid\Layouts\ViewDressWishlistLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Color;
use Orchid\Support\Facades\Toast;

/**
 * This screen allows users to view their wishlisted dresses.
 */
class ViewDressWishlistScreen extends Screen
{
    public string $name = 'Dress Wishlist';
    public ?string $description = 'View your wishlisted dresses';

    /**
     * Get the data for the screen.
     *
     * @param Request $request
     * @return array
     */
    public function query(Request $request): array
    {
        // Retrieve the IDs of wishlisted dresses for the current user
        $wishlistDressesIds = Wishlist::where('user_id', Auth::id())
            ->get()
            ->pluck('dress_id');

        // Fetch dresses based on the wishlisted dress IDs and apply sorting and filtering
        $dresses = Dress::whereIn('dresses.id', $wishlistDressesIds)
            ->filter($request->only(['sort', 'filter']))
            ->latest('created_at')
            ->paginate();

        return [
            'wishlistDresses' => $dresses,
        ];
    }

    /**
     * Define the actions for the screen.
     *
     * @return array
     */
    public function commandBar(): array
    {
        return [
            // Link to go back to the dress listing screen
            Link::make('Back')
                ->route('platform.dresses')
                ->icon('arrow-left'),

            // Button to remove selected dresses from the wishlist
            Button::make('Remove Selected')
                ->method('removeSelectedFromWishlist')
                ->icon('trash')
                ->type(Color::DANGER())
                ->confirm("Are you sure you want to remove the selected dresses from your wishlist?")
        ];
    }

    /**
     * Define the layout of the screen.
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            ViewDressWishlistLayout::class
        ];
    }

    /**
     * Remove selected dresses from the wishlist.
     *
     * @param Request $request
     * @return void
     */
    public function removeSelectedFromWishlist(Request $request)
    {
        $selectedDressesIds = $request->get('selected');
        if (!empty($selectedDressesIds)) {
            Wishlist::whereIn('dress_id', $selectedDressesIds)
                ->where('user_id', Auth::id())
                ->delete();
            Toast::success('Selected dresses successfully removed from wishlist');
        } else {
            Toast::info('No dresses selected to remove from wishlist');
        }
    }
}

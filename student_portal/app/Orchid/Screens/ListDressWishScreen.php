<?php

namespace App\Orchid\Screens;

use App\Models\Dress;
use App\Models\Wishlist;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\DressWishListLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListDressWishScreen extends Screen
{
    public $name = 'Dress Wishlist';

    public $description = '';

    public function query(Request $request): array
    {
        $user = Auth::user();
        $wishlistDressesIds = Wishlist::where('user_id', '=', $user->id)
            ->get()
            ->pluck('dress_id');

        $dresses = Dress::whereIn('dresses.id', $wishlistDressesIds)
            ->filter($request->only(['sort', 'filter']))
            ->latest('created_at')
            ->paginate();

        return [
            'wishlistDresses' => $dresses,
        ];
    }

    public function commandBar()
    {
        return [
            Link::make('Back')
                ->route('platform.dresses')
                ->icon('arrow-left'),
        ];
    }

    public function layout(): array
    {
        return [
            DressWishListLayout::class
        ];
    }
}

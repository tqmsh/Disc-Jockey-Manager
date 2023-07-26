<?php

namespace App\Orchid\Screens;

use App\Models\SchoolDresses;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\DressListLayout;
use App\Models\Dress;
use Illuminate\Http\Request;

class ListDressScreen extends Screen
{
    public $name = 'All Dresses';

    public $description = '';

    public function query(Request $request): array
    {
        return [
            'dresses' => Dress::with('user')
                ->filter(request(['sort', 'filter']))
                ->latest('dresses.created_at')
                ->paginate(),
        ];
    }

    public function commandBar(): array
    {
        $claimedDress = SchoolDresses::where('user_id', Auth::id())->first();

        return [
            Link::make('Wishlist')
                ->route('platform.dresses.wishlist')
                ->icon('heart')
                ->class('btn btn-danger shadow-0'),

            Link::make('Claimed Dress')
                ->route('platform.dresses.claimed')
                ->icon('check')
                ->class($claimedDress ? 'btn btn-warning shadow-0' : 'btn btn-secondary shadow-0 disabled')
        ];
    }

    public function layout(): array
    {
        return [
            DressListLayout::class
        ];
    }
}

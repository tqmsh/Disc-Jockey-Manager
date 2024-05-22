<?php

namespace App\Orchid\Screens;

use App\Models\SchoolDresses;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\ViewDressListLayout;
use App\Models\Dress;
use Orchid\Support\Color;
use Orchid\Support\Facades\Toast;

class ViewDressListScreen extends Screen
{
    public string $name = 'Dress Catalog';
    public string $description = 'Browse for and claim a dress. Be the only one at your event with your dress!';
    public ?SchoolDresses $claimedDress;

    /**
     * Query data
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'dresses' => Dress::with('user')
                ->filter(request(['sort', 'filter']))
                ->latest('dresses.created_at')
                ->paginate(request()->query('pagesize', 10)),
            'claimedDress' => SchoolDresses::where('user_id', Auth::id())->first()
        ];
    }

    /**
     * Command bar buttons
     *
     * @return array
     */
    public function commandBar(): array
    {
        $buttons = [
            Link::make('Wishlist')
                ->route('platform.dresses.wishlist')
                ->icon('heart')
                ->type(Color::DANGER()),
        ];
        if ($this->claimedDress) {
            $buttons[] = Link::make('Claimed Dress')
                ->route('platform.dresses.detail', ['dress' => $this->claimedDress->dress_id, "back" => "platform.dresses"])
                ->icon('check')
                ->type(Color::WARNING());
        } else {
            $buttons[] = Button::make('Claimed Dress')
                ->icon('check')
                ->type(Color::SECONDARY())
                ->style("opacity: 0.65;")
                ->method('noClaimedDress');
        }
        return $buttons;
    }

    /**
     * Layout for current screen
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            ViewDressListLayout::class
        ];
    }

    /**
     * Display message when the user tries to view a non-existent claimed dress.
     *
     * @return void
     */
    public function noClaimedDress(): void
    {
        Toast::warning("You haven't claimed a dress yet!");
    }
}

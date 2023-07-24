<?php

namespace App\Orchid\Screens;

use App\Models\Dress;
use App\Models\SchoolDresses;
use App\Models\Wishlist;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ViewDressDetailScreen extends Screen
{
    public $name = 'Dress';
    public $description = '';
    private $dress;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Dress $dress): array
    {
        $this->dress = $dress;
        return [
            'dress' => $dress
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        // Query if current user has already claimed any dress, or anyone from the same school claimed this dress
        $claimed = SchoolDresses::where(function ($query) {
            $query->where('user_id', auth()->id())
                ->orWhere(function ($subQuery) {
                    $subQuery->where('school_id', auth()->user()->student->school_id)
                        ->where('dress_id', $this->dress->id);
                });
        })->exists();

        $buttons = [
            Link::make('Back')
                ->route('platform.dresses')
                ->icon('arrow-left'),
        ];
        if ($this->dress->url != null) {
            $buttons[] = Link::make('Buy now')
                ->href($this->dress->url)
                ->class('btn btn-success shadow-0')
                ->icon('dollar-sign');
        }

        $inWishlist = Wishlist::where('user_id', auth()->id())
            ->where('dress_id', $this->dress->id)
            ->exists();

        $buttons[] = Button::make($inWishlist ? 'Remove from wishlist' : 'Add to wishlist')
            ->method($inWishlist ? 'removeFromWishlist' : 'addToWishlist')
            ->icon('heart')
            ->class('btn btn-danger shadow-0');

        $buttons[] = Button::make('Claim')
            ->confirm('Are you sure?')
            ->method('claim')
            ->icon('check')
            ->class($claimed ? 'btn btn-secondary shadow-0 disabled' : 'btn btn-warning shadow-0');
        return $buttons;
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::view('dress')
        ];
    }

    public function claim(Dress $dress)
    {
        $claim = new SchoolDresses();
        $claim->dress_id = $dress->id;
        $claim->user_id = auth()->id();
        $claim->school_id = auth()->user()->student->school_id;
        $claim->save();
        Toast::success('Dress claimed!');
    }

    public function addToWishlist(Dress $dress)
    {
        $wishlistItem = new Wishlist();
        $wishlistItem->dress_id = $dress->id;
        $wishlistItem->user_id = auth()->id();
        $wishlistItem->save();
        Toast::success('Dress successfully added to wishlist');
    }

    public function removeFromWishlist(Dress $dress)
    {
        Wishlist::where('dress_id', $dress->id)
            ->where('user_id', auth()->id())
            ->delete();
        Toast::success('Dress successfully removed from wishlist');
    }

}

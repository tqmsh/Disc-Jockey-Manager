<?php

namespace App\Orchid\Screens;

use App\Models\Dress;
use App\Models\SchoolDresses;
use App\Models\Wishlist;
use Exception;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ViewSingleDressScreen extends Screen
{
    public string $name = 'Dress Info';
    public ?string $description = 'Detailed view of a dress. Claim this dress before anyone else at your event does!';
    public Dress $dress;

    /**
     * Query data.
     *
     * @param Dress $dress
     * @return array
     */
    public function query(Dress $dress): array
    {
        return [
            'dress' => $dress
        ];
    }

    /**
     * Button commands.
     *
     * @return array
     */
    public function commandBar(): array
    {
        $buttons = [
            Link::make('Back')
                ->route(request()->get('back') === 'platform.dresses.wishlist' ? 'platform.dresses.wishlist' : 'platform.dresses')
                ->icon('arrow-left'),
        ];
        if ($this->dress->url != null) {
            $buttons[] = Link::make('Buy now')
                ->href($this->dress->url)
                ->type(Color::SUCCESS())
                ->icon('dollar-sign');
        }

        $inWishlist = Wishlist::where('user_id', Auth::id())
            ->where('dress_id', $this->dress->id)
            ->exists();
        $buttons[] = Button::make($inWishlist ? 'Remove from wishlist' : 'Add to wishlist')
            ->method($inWishlist ? 'removeFromWishlist' : 'addToWishlist')
            ->icon('heart')
            ->type(Color::DANGER());

        // Query if current user has already claimed this dress
        $userClaimed = SchoolDresses::where('user_id', Auth::id())
            ->where('dress_id', $this->dress->id)
            ->exists();
        // Query if current user has claimed a different dress
        $userClaimedDifferentDress = SchoolDresses::where('user_id', Auth::id())
            ->where('dress_id', '!=', $this->dress->id)
            ->exists();
        // Query if someone from the same school has already claimed this dress
        $schoolClaimed = SchoolDresses::where('school_id', auth()->user()->student->school_id)
            ->where('dress_id', $this->dress->id)
            ->exists();

        // Add the corresponding button to the command bar
        if ($userClaimed) {
            $buttons[] = Button::make('Unclaim')
                ->confirm('Are you sure you want to unclaim this dress?')
                ->method('unclaim')
                ->icon('minus')
                ->type(Color::WARNING());
        } elseif ($userClaimedDifferentDress) {
            $buttons[] = Button::make('Claim')
                ->method('currentUserAlreadyClaimedDress')
                ->icon('check')
                ->style("opacity: 0.65;")
                ->class('btn btn-secondary shadow-0');
        } elseif ($schoolClaimed) {
            $buttons[] = Button::make('Claim')
                ->method('otherUserAlreadyClaimedDress')
                ->icon('check')
                ->style("opacity: 0.65;")
                ->class('btn btn-secondary shadow-0');
        } else {
            $buttons[] = Button::make('Claim')
                ->confirm('Are you sure you want to claim this dress?')
                ->method('claim')
                ->icon('check')
                ->type(Color::WARNING());
        }
        return $buttons;
    }

    /**
     * Views.
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            Layout::view('dress')
        ];
    }

    /**
     * Claim the selected dress for the current user.
     *
     * @param Dress $dress
     */
    public function claim(Dress $dress): void
    {
        try {
            $claim = new SchoolDresses();
            $claim->dress_id = $dress->id;
            $claim->user_id = auth()->id();
            $claim->school_id = auth()->user()->student->school_id;
            $claim->save();
            Toast::success('Dress claimed!');
        } catch (Exception $e) {
            // Happens when the dress is claimed while the current page is open.
            Toast::error("An unexpected error occurred! Please refresh the page.");
        }
    }

    /**
     * Unclaim the selected dress for the current user.
     *
     * @param Dress $dress
     */
    public function unclaim(Dress $dress): void
    {
        SchoolDresses::where('dress_id', $dress->id)
            ->where('user_id', auth()->id())
            ->delete();
        Toast::success('Dress unclaimed!');
    }

    /**
     * Display an error message if the current user has already claimed a dress.
     */
    public function currentUserAlreadyClaimedDress(): void
    {
        Toast::error("You've already claimed a dress!");
    }

    /**
     * Display an error message if another user has claimed a dress before the current user.
     */
    public function otherUserAlreadyClaimedDress(): void
    {
        Toast::error("Oh no! Someone else already claimed this dress!");
    }

    /**
     * Add the selected dress to the current user's wishlist.
     *
     * @param Dress $dress
     */
    public function addToWishlist(Dress $dress): void
    {
        try {
            $wishlistItem = new Wishlist();
            $wishlistItem->dress_id = $dress->id;
            $wishlistItem->user_id = Auth::id();
            $wishlistItem->save();
            Toast::success('Dress successfully added to wishlist');
        } catch (Exception $e) {
            // Happens when the dresses is added to the wishlist on another tab.
            Toast::error("An unexpected error occurred! Please refresh the page.");
        }
    }

    /**
     * Remove the selected dress from the current user's wishlist.
     *
     * @param Dress $dress
     */
    public function removeFromWishlist(Dress $dress): void
    {
        Wishlist::where('dress_id', $dress->id)
            ->where('user_id', Auth::id())
            ->delete();
        Toast::success('Dress successfully removed from wishlist');
    }

}

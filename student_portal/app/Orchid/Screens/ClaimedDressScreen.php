<?php

namespace App\Orchid\Screens;

use App\Models\SchoolDresses;
use Illuminate\Support\Facades\Redirect;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimedDressScreen extends Screen
{
    public $name = 'Claimed Dress';

    public $description = '';

    public function query(Request $request): array
    {
        $user = Auth::user();
        $this->claimedDress = SchoolDresses::where('user_id', '=', $user->id)->first();

        return [
            'claimedDress' => $this->claimedDress,
        ];
    }

    public function commandBar(): array
    {
        $claimedDress = SchoolDresses::where('user_id', '=', Auth::user()->id)->first();

        return [
            Link::make('Back')
                ->route('platform.dresses')
                ->icon('arrow-left'),
            Button::make('Unclaim Current Dress')
                ->method('unclaimDress')
                ->canSee($claimedDress != null)
                ->icon('minus')
                ->class('btn btn-warning shadow-0')
                ->confirm("This will give other people a chance to claim your dress!")
        ];
    }

    public function unclaimDress(Request $request)
    {
        $user = Auth::user();
        $claimedDress = SchoolDresses::where('user_id', '=', $user->id)->first();
        if ($claimedDress) {
            $claimedDress->delete();
            Toast::info(__('Dress unclaimed successfully.'));
            return Redirect::route('platform.dresses');
        }

        return back();
    }

    public function layout(): array
    {
        return [
            Layout::view('dress', ['dress' => $this->claimedDress ? $this->claimedDress->dress : null]),
        ];
    }
}

<?php

namespace App\Orchid\Screens;

use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewWinnersLayout;

class ViewWinnersScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Election $election) : array
    {
        return [];
    }  

    /**
     * The name of the screen is displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return "Election Winners";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar() : array
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout() : array
    {
        return [];
    }
}
//     public $election;
//     /**
//      * Query data.
//      *
//      * @return array
//      */
//     public function query(Election $election): iterable
//     {
//         // $candidate = Candidate::where('position_id', $position->id)->paginate(10);
//         $election = Election::where('id', $election->id)->first();

//         return [
//             // 'position' => $position,
//             // 'candidate' => $candidate,
//             'election' => $election,
//         ];
//     }

//     /**
//      * Display header name.
//      *
//      * @return string|null
//      */
//     public function name(): ?string
//     {
//         return 'ViewWinnersScreen';
//     }

//     /**
//      * Button commands.
//      *
//      * @return \Orchid\Screen\Action[]
//      */
//     public function commandBar(): iterable
//     {
//         return [
//             // Link::make('Back')
//             //     ->icon('arrow-left')
//             //     ->route('platform.election.list', $this->election->event_id)
//         ];
//     }

//     /**
//      * Views.
//      *
//      * @return \Orchid\Screen\Layout[]|string[]
//      */
//     public function layout(): iterable
//     {
//         return [
//             Layout::modal('HELLO WORLD', [
//                 Layout::rows([]),
//             ])->withoutApplyButton()->open(),

//             ViewWinnersLayout::class,
//         ];
//     }
// }

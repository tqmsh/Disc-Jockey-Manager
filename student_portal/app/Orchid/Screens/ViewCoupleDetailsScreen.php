<?php

namespace App\Orchid\Screens;

use App\Models\Couple;
use App\Models\User;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Sight;

class ViewCoupleDetailsScreen extends Screen
{

    public Couple $couple;

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return User::find($this->couple->student_user_id_1)->fullName()
            ." & ".
            User::find($this->couple->student_user_id_2)->fullName();
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Couple $couple): iterable
    {
        $this->couple = $couple;
        return [];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.promdate')
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::legend($this->couple, [
                Sight::make('couple_name', 'Couple Name')->render(function (Couple $couple = null) {
                    if ($this->couple->couple_name) {
                        return $this->couple->couple_name;
                    } else {
                        return "Unnamed Couple";
                    }
                }),

                Sight::make('status', 'Status')->render(function (Couple $couple = null) {
                    if ($this->couple->status) {
                        return $this->couple->status;
                    } else {
                        return "Unknown";
                    }
                }),

                Sight::make('description', 'Description')->render(function (Couple $couple = null) {
                    if ($this->couple->description) {
                        return $this->couple->description;
                    } else {
                        return "Nothing here yet... What were you expecting?";
                    }
                }),
            ]),
        ];
    }

}

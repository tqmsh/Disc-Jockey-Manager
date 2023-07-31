<?php

namespace App\Orchid\Screens;

use App\Models\Dress;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;

class ViewDressDetailScreen extends Screen
{
    public string $name = 'Dress Preview';
    public ?string $description = 'Provides a replica of the dress view as it would appear to students.';
    public Dress $dress;

    /**
     * Query data.
     *
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
     * @return Link[]
     */
    public function commandBar(): array
    {
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
        $buttons[] = Button::make('Add to wishlist')
            ->icon('heart')
            ->class('disabled btn btn-danger shadow-0');
        $buttons[] = Button::make('Claim')
            ->icon('check')
            ->class('disabled btn btn-warning shadow-0');
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

}

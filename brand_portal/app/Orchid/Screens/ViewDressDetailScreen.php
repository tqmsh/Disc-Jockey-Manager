<?php

namespace App\Orchid\Screens;

use App\Models\Dress;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;

class ViewDressDetailScreen extends Screen
{
    public $name = 'Preview Dress';
    public $description = '';
    public $dress;

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
        return [
            Link::make('Back')
                ->route('platform.dresses.edit', ['dress' => $this->dress])
                ->icon('arrow-left'),
        ];
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

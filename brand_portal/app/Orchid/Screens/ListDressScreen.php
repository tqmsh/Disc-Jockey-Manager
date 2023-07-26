<?php

namespace App\Orchid\Screens;

use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\DressListLayout;
use App\Models\Dress;
use Illuminate\Http\Request;

class ListDressScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Dresses';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = '';

    private $selected = [];

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Request $request): array
    {
        return [
            'dresses' => Dress::where('user_id', Auth::user()->id)
                ->filter(request(['sort', 'filter']))
                ->latest('dresses.created_at')->paginate()
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
            Link::make('Create new')
                ->type(Color::PRIMARY())
                ->icon('plus')
                ->route('platform.dresses.edit'),

            Link::make('Bulk upload')
                ->type(Color::WARNING())
                ->icon('upload')
                ->route('platform.dresses.upload'),

            Button::make('Delete selected')
                ->type(Color::DANGER())
                ->icon('trash')
                ->confirm('Are you sure you want to delete the selected dresses?')
                ->method('deleteSelected'),
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
            DressListLayout::class
        ];
    }

    public function deleteSelected(Request $request)
    {
        $selected = $request->get('selected');

        if (empty($selected)) {
            Toast::warning('No dresses were selected.');
        } else {
            Dress::destroy($selected);
            Toast::success('Selected dresses were deleted.');
        }
    }
}

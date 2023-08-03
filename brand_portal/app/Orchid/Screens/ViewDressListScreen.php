<?php

namespace App\Orchid\Screens;

use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewDressListLayout;
use App\Models\Dress;
use Illuminate\Http\Request;

class ViewDressListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public string $name = 'Dresses';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public ?string $description = 'Manage your collection of dresses.';


    /**
     * Query data. Filters and sorts the dresses based on the user request.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'dresses' => Dress::where('user_id', Auth::id())
                ->filter(request(['sort', 'filter']))
                ->latest('dresses.created_at')->paginate()
        ];
    }

    /**
     * Button commands. Defines the actions available to the user.
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
                ->method('deleteSelectedDresses'),
        ];
    }

    /**
     * Views. Defines the layouts to be used.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            ViewDressListLayout::class
        ];
    }

    /**
     * Delete selected dresses. If no dresses are selected, a warning is issued.
     */
    public function deleteSelectedDresses(Request $request)
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

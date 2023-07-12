<?php

namespace App\Orchid\Screens;

use Illuminate\Support\Facades\Schema;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\DressListLayout;
use App\Models\Dress;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        $query = Dress::where('user_id', $user->id);

        if (array_key_exists('sort', $request->toArray()) && strlen($request->get('sort'))) {
            $sortColumn = $request->get('sort'); // Get the sort column from the request
            if ($sortColumn[0] == '-') {
                $sortDirection = 'desc';
                $sortColumn = substr($sortColumn, 1);
            } else {
                $sortDirection = 'asc';
            }
            if (in_array($sortColumn, Schema::getColumnListing('dresses'))) {
                $query->orderBy($sortColumn, $sortDirection);
            }
        }

        $filterValueModelNumber = $request->get('filter')['model_number'] ?? null;
        $filterValueModelName = $request->get('filter')['model_name'] ?? null;
        $filterValueUrl = $request->get('filter')['url'] ?? null;
        $filterValueCreatedAt = $request->get('filter')['created_at'] ?? null;
        $filterValueLastUpdated = $request->get('filter')['last_updated'] ?? null;

        // Apply filtering
        if ($filterValueModelNumber) {
            $query->where('model_number', 'LIKE', "%{$filterValueModelNumber}%");
        }
        if ($filterValueModelName) {
            $query->where('model_name', 'LIKE', "%{$filterValueModelName}%");
        }
        if ($filterValueUrl) {
            $query->where('url', 'LIKE', "%{$filterValueUrl}%");
        }
        if ($filterValueCreatedAt) {
            $query->whereDate('created_at', '=', $filterValueCreatedAt);
        }
        if ($filterValueLastUpdated) {
            $query->whereDate('last_updated', '=', $filterValueLastUpdated);
        }

        return [
            'dresses' => $query->paginate(),
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
            Alert::warning('No dresses were selected.');
        } else {
            Dress::destroy($selected);
            Alert::success('Selected dresses were deleted.');
        }
    }
}

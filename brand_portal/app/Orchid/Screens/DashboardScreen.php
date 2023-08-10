<?php

namespace App\Orchid\Screens;

use App\Models\Student;
use Exception;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Repository;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\SchoolDresses;
use App\Models\Wishlist;
use App\Models\School;
use Orchid\Support\Facades\Toast;

class DashboardScreen extends Screen
{
    public string $name = 'Dashboard';
    public ?string $description = 'View the number of students that have wishlisted or claimed your dresses by location.';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        try {
            $sort_param = request('sort');
            $filter_param = request('filter');

            $vendorId = Auth::id();
            $claimedDresses = SchoolDresses::whereHas('dress', function ($query) use ($vendorId) {
                $query->where('user_id', $vendorId);
            })->get();
            $wishlistedDresses = Wishlist::whereHas('dress', function ($query) use ($vendorId) {
                $query->where('user_id', $vendorId);
            })->get();

            if (!empty($filter_param)) {
                $claimedDresses = $claimedDresses->filter(function ($obj) use ($filter_param) {
                    foreach ($filter_param as $key => $value) {
                        if ($obj->school[$key] !== $value) {
                            return false;
                        }
                    }
                    return true;
                });
                $wishlistedDresses = $wishlistedDresses->filter(function ($obj) use ($filter_param) {
                    $school = School::where('id', Student::where('user_id', $obj->user->id)->first()->school_id)->first();
                    foreach ($filter_param as $key => $value) {
                        if ($school[$key] !== $value) {
                            return false;
                        }
                    }
                    return true;
                });
            }

            $location_count = [];
            foreach ($claimedDresses as $obj) {
                $key = $obj->school->country . '_' . $obj->school->state_province . '_' . $obj->school->city_municipality;
                if (!array_key_exists($key, $location_count)) {
                    $location_count[$key] = new Repository([
                        'country' => $obj->school->country,
                        'state_province' => $obj->school->state_province,
                        'city_municipality' => $obj->school->city_municipality,
                        'claimed_dresses' => 1,
                        'wishlisted_dresses' => 0,
                    ]);
                } else {
                    $location_count[$key]['claimed_dresses'] += 1;
                }
            }
            foreach ($wishlistedDresses as $obj) {
                $school = School::where('id', Student::where('user_id', $obj->user->id)->first()->school_id)->first();
                $key = $school->country . '_' . $school->state_province . '_' . $school->city_municipality;
                if (!array_key_exists($key, $location_count)) {
                    $location_count[$key] = new Repository([
                        'country' => $school->country,
                        'state_province' => $school->state_province,
                        'city_municipality' => $school->city_municipality,
                        'claimed_dresses' => 0,
                        'wishlisted_dresses' => 1,
                    ]);
                } else {
                    $location_count[$key]['wishlisted_dresses'] += 1;
                }
            }

            if (!empty($sort_param)) {
                $order = (strpos($sort_param, '-') === 0) ? 'desc' : 'asc';
                $sort_param = ltrim($sort_param, '-');

                usort($location_count, function ($a, $b) use ($sort_param, $order) {
                    return ($order === 'asc')
                        ? $a[$sort_param] <=> $b[$sort_param]
                        : $b[$sort_param] <=> $a[$sort_param];
                });
            }
            return [
                'dressesClaimed' => count($claimedDresses),
                'dressesWishlisted' => count($wishlistedDresses),
                'locations' => $location_count
            ];
        } catch (Exception $e) {
            Toast::error('There was an error processing the filter. Error Message: ' . $e);
        }
        return [
            'dressesClaimed' => -1,
            'dressesWishlisted' => -1,
            'locations' => []
        ];
    }

    /**
     * Button commands.
     *
     * @return array
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Group::make([
                    Select::make('filter.country')
                        ->title('Country:')
                        ->empty('No selection')
                        ->value(fn() => request('filter.country'))
                        ->fromModel(School::class, 'country', 'country'),

                    Select::make('filter.state_province')
                        ->title('State/Province')
                        ->empty('No selection')
                        ->value(fn() => request('filter.state_province'))
                        ->fromModel(School::class, 'state_province', 'state_province'),

                    Select::make('filter.city_municipality')
                        ->title('City/Municipality')
                        ->empty('No selection')
                        ->value(fn() => request('filter.city_municipality'))
                        ->fromModel(School::class, 'city_municipality', 'city_municipality'),
                ]),

                Group::make([
                    Button::make('Filter')
                        ->icon('filter')
                        ->method('filter'),

                    Button::make('Clear Filters')
                        ->icon('close')
                        ->method('clearFilters')
                ])
            ]),

            Layout::metrics([
                'Dresses Wishlisted' => 'dressesWishlisted',
                'Dresses Claimed' => 'dressesClaimed',
            ])->title('Summary Metrics'),

            Layout::table('locations', [
                TD::make('country', 'Country')
                    ->sort(),
                TD::make('state_province', 'State/Province')
                    ->sort(),
                TD::make('city_municipality', 'City/Municipality')
                    ->sort(),
                TD::make('wishlisted_dresses', 'Wishlisted Dresses')
                    ->sort(),
                TD::make('claimed_dresses', 'Claimed Dresses')
                    ->sort(),
            ])->title('By Location'),
        ];
    }

    public function filter()
    {
        return redirect()->route('platform.dashboard', request(['sort', 'filter']));
    }

    public function clearFilters()
    {
        return redirect()->route('platform.dashboard');
    }
}

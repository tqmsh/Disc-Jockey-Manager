<?php

namespace App\Orchid\Filters;

use App\Models\Localadmin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;

class StudentNumFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return '';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['startdate', 'enddate'];
    }

    /**
     * Apply to a given Eloquent query builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $user = Auth::user();
        $localAdmin = LocalAdmin::where('user_id', $user->id)->first();
        $schoolId = $localAdmin->school_id;
        $startDate = $this->request->get('startdate') ." 00:00:00" ?? "";
        $endDate = $this->request->get('enddate') . " 23:59:59" ?? "";
        return $builder->selectRaw('count(*), date(created_at)')->where('school_id', $schoolId)->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date(created_at)');

    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        //
    }
}

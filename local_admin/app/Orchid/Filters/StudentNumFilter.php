<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
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
        return ['startdate', 'enddate', 'schoolId'];
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
        $startDate = $this->request->get('startdate') ." 00:00:00" ?? "";
        $endDate = $this->request->get('enddate') . " 23:59:59" ?? "";
        $schoolId = $this->request->get('schoolId');
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

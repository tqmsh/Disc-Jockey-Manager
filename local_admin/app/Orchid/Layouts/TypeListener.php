<?php

namespace App\Orchid\Layouts;

use Illuminate\Http\Request;
use Orchid\Screen\Repository;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Layouts\Listener;

class TypeListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'type.',
        'minuend',
        'subtrahend',
    ];

    /**
     * @return Layout[]
     */
    protected function layouts(): iterable
    {
        return [
            Layout::rows([
                Input::make('minuend')
                    ->title('First argument')
                    ->type('number'),

                Input::make('subtrahend')
                    ->title('Second argument')
                    ->type('number'),

                Input::make('result')
                    ->readonly()
                    ->canSee($this->query->has('result')),
            ]),
        ];
    }

    /**
     * @param \Orchid\Screen\Repository $repository
     * @param \Illuminate\Http\Request  $request
     *
     * @return \Orchid\Screen\Repository
     */
    public function handle(Repository $repository, Request $request): Repository
    {
        [$minuend, $subtrahend] = $request->all();
        dd($repository);
        return $repository;
            //->set('minuend', $minuend)
            //->set('subtrahend', $subtrahend)
            //->set('result', $minuend - $subtrahend);
    }
}

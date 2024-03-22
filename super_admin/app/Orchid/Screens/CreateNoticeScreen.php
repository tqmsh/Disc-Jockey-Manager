<?php

namespace App\Orchid\Screens;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CreateNoticeScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Add a New Notice';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Add')
                ->icon('plus')
                ->method('createNotice'),
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.notice.list'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Select::make('dashboard')
                    ->title('Type')
                    ->options(Notice::$dashboard_names)
                    ->required(),
                Input::make('content')
                    ->title('Content')
                    ->type('text')
                    ->required(),
                Input::make('url')
                    ->title('URL')
                    ->type('text'),
            ])
        ];
    }

    public function createNotice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dashboard' => [
                'required',
                'unique:notices',
                'integer',
                Rule::in(array_keys(Notice::$dashboard_names)),
            ],
            'content' => [
                'required',
                'max:255',
            ],
            'url' => [
                'nullable',
                'url',
                'max:255',
            ]
        ],
        $messages = [
            'dashboard.unique' => 'A notice already exists for this dashboard.'
        ]);
        Notice::create($validator->validated());
        Toast::success('Notice added succesfully');
        return redirect()->route('platform.notice.list');
    }
}

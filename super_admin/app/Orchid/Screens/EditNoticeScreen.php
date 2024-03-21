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

class EditNoticeScreen extends Screen
{
    public $notice;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Notice $notice): iterable
    {
        return [
            'notice' => $notice,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit ' . $this->notice->getDashboardName() . ' Notice';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Submit')
                ->icon('check')
                ->method('update'),
            Button::make("Delete Notice")
                ->icon('trash')
                ->method('delete')
                ->confirm(__('Are you sure you want to delete this notice?')),
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
                    ->required()
                    ->value($this->notice->dashboard),
                Input::make('content')
                    ->title('Content')
                    ->type('text')
                    ->required()
                    ->value($this->notice->content),
            ])
        ];
    }

    public function update(Notice $Notice, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dashboard' => [
                'required',
                Rule::unique('notices')->ignore($Notice),
                'integer',
                Rule::in(array_keys(Notice::$dashboard_names)),
            ],
            'content' => [
                'required',
                'max:255',
            ]
        ],
        $messages = [
            'dashboard.unique' => 'A notice already exists for this dashboard.'
        ]);
        $Notice->update($validator->validate());
        Toast::success('Notice edited succesfully');
    }

    public function delete(Notice $Notice)
    {
        $Notice->delete();
        Toast::success('Notice deleted succesfully');
        return redirect()->route('platform.notice.list');
    }
}

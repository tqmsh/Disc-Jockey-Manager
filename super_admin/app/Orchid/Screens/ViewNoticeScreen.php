<?php

namespace App\Orchid\Screens;

use App\Models\Notice;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ViewNoticeScreen extends Screen
{
    public $notices;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'notices' => Notice::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Notices';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Add New Notices')
                ->icon('plus')
                ->route('platform.notice.create'),
            Button::make('Delete Selected Notices')
                ->icon('trash')
                ->method('deleteNotices')
                ->confirm(__('Are you sure you want to delete the selected notices?')),
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
            Layout::table('notices', [
                TD::make('checkboxes')
                    ->render(function (Notice $notice) {
                        return CheckBox::make('notices[]')
                            ->value($notice->id)
                            ->checked(false);
                    }),
                TD::make('dashboard', 'Dashboard')
                    ->render(function (Notice $notice) {
                        return Link::make($notice->getDashboardName())
                            ->route('platform.notice.edit', $notice);
                    }),
                TD::make('title', 'Title')
                    ->render(function (Notice $notice) {
                        return Link::make($notice->title)
                            ->route('platform.notice.edit', $notice);
                    }),
                TD::make('subtitle', 'Subtitle')
                    ->render(function (Notice $notice) {
                        return Link::make($notice->subtitle)
                            ->route('platform.notice.edit', $notice);
                    }),
                TD::make('url', 'URL')
                    ->render(function (Notice $notice) {
                        if (!is_null($notice->url)) {
                            return Link::make($notice->url)
                                ->href($notice->url)->set('target', '_blank');
                        }
                    }),
                TD::make()
                    ->render(function (Notice $notice) {
                        return Link::make('Edit')
                            ->type(Color::PRIMARY())
                            ->route('platform.notice.edit', $notice)
                            ->icon('pencil');
                    }),
            ]),
        ];
    }

    public function deleteNotices(Request $request)
    {
        $notices = $request->get('notices');
        if (!empty($notices)) {
            Notice::whereIn('id', $notices)->delete();
            Toast::success('Selected notices deleted succesfully');
        } else {
            TOast::warning('You must select notices in order to delete them');
        }
    }
}

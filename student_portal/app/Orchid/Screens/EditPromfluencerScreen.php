<?php

namespace App\Orchid\Screens;

use App\Models\Promfluencer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditPromfluencerScreen extends Screen
{
    public $promfluencer;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'promfluencer' => Promfluencer::where('user_id', Auth::id())->firstOrFail(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Promfluence';
    }

    /**
     * Display description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Instructional/Disclaimer Text Placeholder';
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
                ->method('updatePromfluencer'),
            Button::make('Delete Promfluence')
                ->icon('trash')
                ->method('deletePromfluencer')
                ->confirm('Are you sure you would like to delete your Promfluence?'),
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.promfluencer.view'),
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
                Input::make('name')
                    ->title('Name')
                    ->type('text')
                    ->horizontal()
                    ->value($this->promfluencer->user->firstname . ' ' . $this->promfluencer->user->lastname)
                    ->disabled(),
                Input::make('email')
                    ->title('Email')
                    ->type('email')
                    ->horizontal()
                    ->value($this->promfluencer->user->email)
                    ->disabled(),
                Input::make('phonenumber')
                    ->title('Phone Number')
                    ->type('text')
                    ->horizontal()
                    ->value($this->promfluencer->user->phonenumber)
                    ->disabled(),
                Input::make('school')
                    ->title('School')
                    ->type('text')
                    ->horizontal()
                    ->value($this->promfluencer->user->student->school)
                    ->disabled(),
                Input::make('grade')
                    ->title('Grade')
                    ->type('text')
                    ->horizontal()
                    ->value($this->promfluencer->user->student->grade)
                    ->disabled(),
                
                Input::make('instagram')
                    ->title('Instagram')
                    ->type('text')
                    ->horizontal()
                    ->value($this->promfluencer->instagram),
                Input::make('tiktok')
                    ->title('TikTok')
                    ->type('text')
                    ->horizontal()
                    ->value($this->promfluencer->tiktok),
                Input::make('snapchat')
                    ->title('Snapchat')
                    ->type('text')
                    ->horizontal()
                    ->value($this->promfluencer->snapchat),
                Input::make('youtube')
                    ->title('YouTube')
                    ->type('text')
                    ->horizontal()
                    ->value($this->promfluencer->youtube),
            ])
        ];
    }

    function updatePromfluencer(Request $request)
    {
        $promfluencer = Promfluencer::firstWhere('user_id', Auth::id());
        if ($promfluencer === NULL) {
            Toast::error('Promfluence does not exist');
            return;
        }
        $validated = $request->validate([
            'instagram' => 'nullable|max:255',
            'tiktok' => 'nullable|max:255',
            'snapchat' => 'nullable|max:255',
            'youtube' => 'nullable|max:255',
        ]);
        $promfluencer->update($validated);
        Toast::success('Promfluence updated succesfully');
    }

    function deletePromfluencer()
    {
        $promfluencer = Promfluencer::firstWhere('user_id', Auth::id());
        if ($promfluencer === NULL) {
            Toast::error('Promfluence does not exist');
            return;
        }
        $promfluencer->delete();
        Toast::success('Promfluence deleted succesfully');
        return redirect()->route('platform.promfluencer.view');
    }
}

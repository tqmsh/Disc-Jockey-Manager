<?php

namespace App\Orchid\Screens;

use App\Models\LoginAds;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditLoginAdScreen extends Screen
{

    /**
     * @var LoginAds
     */
    public $loginAd;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(LoginAds $loginAd): iterable
    {
        return [
            'loginAd' => $loginAd
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Editing Login Ad: ' . $this->loginAd->title;
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

            Button::make('Back')
                ->icon('arrow-left')
                ->method('redirect')
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
                Select::make('portal')
                    ->title('Portal')
                    ->options([
                        2 => 'Local Admin',
                        3 => 'Student',
                        4 => 'Vendor'
                    ])
                    ->value($this->loginAd->portal),
                
                Input::make('title')
                    ->title('Title')
                    ->placeholder('e.g. Prom Committee Expo')
                    ->value($this->loginAd->title),

                Input::make('subtitle')
                    ->title('Subtitle')
                    ->placeholder('e.g. Where Prom Committees get the best seminars and deals!')
                    ->value($this->loginAd->subtitle),

                Input::make('button_title')
                    ->title('Button Title')
                    ->placeholder('e.g. Visit the Site NOW')
                    ->value($this->loginAd->button_title),

                Input::make('website')
                    ->title('Website URL')
                    ->placeholder('e.g. https://promcommitteeexpo.com/')
                    ->value($this->loginAd->campaign->website),
                
                Input::make('image')
                    ->title('Background Image URL')
                    ->placeholder('e.g. https://www.simplilearn.com/ice9/free_resources_article_thumb/what_is_image_Processing.jpg')
                    ->value($this->loginAd->campaign->image),
            ])
        ];
    }

    public function update(Request $request, LoginAds $loginAd) {
        try {
            $data = $request->validate([
                'portal' => 'required',
                'title' => 'required',
                'subtitle' => 'required',
                'button_title' => 'required',
                'image' => 'required',
                'website' => 'required'
            ]); 

            $loginAd->campaign->update([
                'title' => $data['title'],
                'website' => $data['website'],
                'image' => $data['image']
            ]);

            // Exclude website and image keys.
            $loginAd->update(array_diff_key($data, array_flip(['website', 'image'])));

            Toast::success('Successfully updated this login ad!');

            return $this->redirect();
        } catch(\Exception $e) {
            Alert::error("There was an error trying to update this login ad. Error message: {$e->getMessage()}");
        }
    }

    public function redirect() {
        return to_route('platform.ad.list', ['active_tab' => 'Login/Register Ads']);
    }
}

<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use App\Models\LoginAs;

class ViewLoginAsGeneratedScreen extends Screen
{

    /**
     * @var LoginAs $loginAs
     */
    public $loginAs;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(LoginAs $loginAs): iterable
    {
        return [
            'loginAs' => $loginAs
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return '';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::modal('Login as key generated.', [
                Layout::rows([
                    Link::make('Redirect to login as page')
                        ->target('_blank')
                        ->href("https://{$this->loginAs->portalToTarget()}/login-as/{$this->loginAs->la_key}")
                ])
            ])
                ->open(true)
                ->withoutApplyButton()
                ->withoutCloseButton()
        ];
    }
}

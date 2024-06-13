<?php

namespace App\Orchid\Layouts;

use App\Models\Campaign;
use App\Models\LoginAds;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class ViewLoginAds extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'campaignsLoginAds';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('checkboxes')
                ->render(function(LoginAds $lgAd) {
                    return CheckBox::make('campaignsSelected[]')
                            ->value($lgAd->campaign_id)
                            ->checked(false);
                }),

            TD::make()
                ->width('80')
                ->align(TD::ALIGN_LEFT)
                ->render(function(LoginAds $lgAd) {
                    return Button::make('Edit')
                            ->type(Color::PRIMARY())
                            ->method('redirectLoginAd', ['login_ad_id' => $lgAd->id])
                            ->icon('pencil');
                }),

            TD::make('Portal')
                ->render(fn(LoginAds $lgAd) => e($lgAd->portalToName())),

            TD::make('Title')
                ->render(fn(LoginAds $lgAd) => e($lgAd->title)),

            TD::make('Subtitle')
                ->render(fn(LoginAds $lgAd) => e($lgAd->subtitle)),

            TD::make('Button Title')
                ->render(fn(LoginAds $lgAd) => e($lgAd->button_title)),

            TD::make('Image')
                ->render(function(LoginAds $lgAd){
                    return "<img src='{$lgAd->campaign->image}' width='200'>";
                }),

            TD::make('Website')
                ->render(fn(LoginAds $lgAd) => Link::make($lgAd->campaign->website)->href($lgAd->campaign->website)->target('_blank')),
        ];
    }
}

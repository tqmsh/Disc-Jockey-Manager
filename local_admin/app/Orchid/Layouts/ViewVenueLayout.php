<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Venues; // Updated model
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ViewVenueLayout extends Table
{
    protected $target = 'venues'; // Target collection

    protected function columns(): iterable
    {
        return [
            TD::make('checkboxes')
                ->render(function (Venues $venue) {
                    return CheckBox::make('venues[]')
                        ->value($venue->id)
                        ->checked(false);
                }),

            TD::make('name', 'Name of Venue')
                ->render(function (Venues $venue) {
                    return Link::make($venue->name)
                        ->route('platform.venue.edit', $venue);
                }),

            TD::make('address', 'Address')
                ->render(function (Venues $venue) {
                    return $venue->address;
                }),

            TD::make('city', 'City')
                ->render(function (Venues $venue) {
                    return $venue->city;
                }),

            TD::make('state_province', 'State/Province')
                ->render(function (Venues $venue) {
                    return $venue->state_province;
                }),

            TD::make('country', 'Country')
                ->render(function (Venues $venue) {
                    return $venue->country;
                }),

            TD::make('zip_postal', 'Zip/Postal')
                ->render(function (Venues $venue) {
                    return $venue->zip_postal;
                }),

            TD::make('website', 'Website')
                ->render(function (Venues $venue) {
                    return $venue->website;
                }),

            TD::make('contact_first_name', 'Contact First Name')
                ->render(function (Venues $venue) {
                    return $venue->contact_first_name;
                }),

            TD::make('contact_last_name', 'Contact Last Name')
                ->render(function (Venues $venue) {
                    return $venue->contact_last_name;
                }),

            TD::make('email', 'Email')
                ->render(function (Venues $venue) {
                    $email = strlen($venue->email) <= 25 ? $venue->email : substr($venue->email, 0, 25) . '...';
                    return Link::make($email)
                        ->route('platform.venue.edit', $venue);
                }),

            TD::make('phone', 'Phone')
                ->render(function (Venues $venue) {
                    return $venue->phone;
                }),

            TD::make()
                ->render(function (Venues $venue) {
                    return Button::make('Edit')
                        ->type(Color::PRIMARY())
                        ->method('redirect', ['venue' => $venue->id, 'type' => 'venues'])
                        ->icon('pencil');
                }),
        ];
    }
}

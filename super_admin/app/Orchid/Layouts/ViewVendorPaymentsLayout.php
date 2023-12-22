<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;


use App\Models\Payment;
use App\Models\User;
use App\Models\Vendors;
use App\Models\Categories;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ViewVendorPaymentsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'payments';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('payment_amount', 'Payment Amount')
                ->render(function (Payment $payments) {
                    return '$' . $payments->payment_amount;
                }),
            
            TD::make('credits_given', 'Credits Given')
                ->render(function (Payment $payments) {
                    return $payments->credits_given;
                }),
            TD::make('created_at', 'Date')
                ->render(function (Payment $payments) {
                    return $payments->created_at;
                }),
        ];
    }
}

<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\Vendors;
use App\Models\Payment;

use App\Models\Categories;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Password;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewVendorPaymentsLayout;


class ViewVendorPaymentsScreen extends Screen
{
    public $vendor;
    public $user;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Vendors $vendor): iterable
    {
        return [
            'vendor' => $vendor,
            'user' => User::find($vendor->user_id),
            'payments' => Payment::latest('payments.created_at')->where('user_id', $vendor->user_id)->paginate(request()->query('pagesize', 10)),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Payment History: ' . $this->user->name;
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.vendor.credits')
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
            ViewVendorPaymentsLayout::class,
        ];
    }
}

<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use App\Models\Payment;
use App\Models\Vendors;
use Illuminate\Support\Facades\Auth;




use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalclient;
// Commit test
class BuyCreditsScreen extends Screen
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
        return 'Buy Credits';
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
            Layout::view('buy_credits')
        ];
    }

    public function payment(Request $request) {

        $provider = new PaypalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal_success'),
                "cancel_url" => route('paypal_cancel')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "CAD",
                        "value" => $request->price
                    ]
                ]
            ]
        ]); 


        if(isset($response['id']) && $response['id']!=null) {
            foreach($response['links'] as $link) {
                if($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        } else {
            return redirect()->route('paypal_cancel');
        }

        
    }

    public function success(Request $request) {

        // dd($request->input('credits'))
        $provider = new PaypalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response =  $provider->capturePaymentOrder($request->token);

        $price = $request->input('price');
        $credits = $request->input('credits');

        // Last check to see if the Payment went trough successfully
        if(isset($response['status']) && $response['status'] == 'COMPLETED') {

            $vendor = Vendors::where('user_id', Auth::user()->id)->first();

            Payment::create([
                'user_id' => $vendor->user_id,
                'credits_given' => $credits,
                'payment_amount' => $price, // Assign the price to payment_amount
                'date' => now(),
            ]);
            

            Toast::success('Credits bought Successfully!');

            return redirect()->route('platform.shop');

        } else { 
            return redirect()->route('paypal_cancel');

        }
    }

    public function cancel() {
        Toast::error('Error');
    }

}

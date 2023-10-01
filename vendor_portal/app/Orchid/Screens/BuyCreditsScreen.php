<?php

namespace App\Orchid\Screens;
use Illuminate\Support\Facades\DB;
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
    private $price_1;
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

        $vendor = Vendors::where('user_id', Auth::user()->id)->first();

        return [
            Layout::view('buy_credits')
        ];
    }

    public function payment(Request $request) {

        // dd($request->price);

        // $price = $request->price;
        // $credits = $request->input('credits');

        // dd($price, $credits);

        // dd($request);

        // $this->price_1 = $request->input('price');

        $provider = new PaypalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                // Success function gets called here
                "return_url" => route('paypal_success',['price' => $request->price,'credits' => $request->credits]),
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

    public function success(Request $request, $price, $credits) {

        // dd($price, $credits);

        // dd($request->input('credits'), )
        $provider = new PaypalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response =  $provider->capturePaymentOrder($request->token);

        // $price = $request->input('price');
        // $credits = $request->input('credits');

        // dd($price, $credits);
        // dd($this->price_1);

        // Last check to see if the Payment went trough successfully
        if(isset($response['status']) && $response['status'] == 'COMPLETED') {

            $vendor = Vendors::where('user_id', Auth::user()->id)->first();

            // Add Data to the database
            Payment::create([
                'user_id' => $vendor->user_id,
                'credits_given' => $credits,
                'payment_amount' => $price, // Assign the price to payment_amount
                'date' => now(),
            ]);

            $vendor->increment('credits', $credits);
            $vendor->save();
            

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

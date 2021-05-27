<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe;
use Session;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        \Stripe\Stripe::setApiKey('sk_test_51Iuxp6SGCTPXtTRvWZVxbBvVPSltxDF6Nw8GuuPOKmncFp5U6FtnLHyqqKshtNBMQ3ib6IXqQvIo86a1rwBxLYg800bZ6p47lK');
        		
		$amount = 100;
		$amount *= 100;
        $amount = (int) $amount;
        
        $payment_intent = \Stripe\PaymentIntent::create([
			'description' => 'Stripe Test Payment',
			'amount' => $amount,
			'currency' => 'INR',
			'description' => 'Test Payment',
			'payment_method_types' => ['card'],
		]);
		$intent = $payment_intent->client_secret;

		return view('stripeWelcome',compact('intent'));        
    }

    public function afterPayment()
    {
        echo 'Payment Has been Received';
    }

    public function failedPayment()
    {
        echo 'Payment Has been Failed <br><a href="javascript:history.back()">try again</a>';
    }    
}
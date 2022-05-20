<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Models\Order;
use App\Repositories\Contracts\ITransaction;
use App\Services\Paystack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    protected $transactions;

    public function __construct(ITransaction $transactions)
    {
        $this->transactions = $transactions;
    }

    public function pay(Order $order)
    {
        request()->validate([
            'email' => ['required', 'email'],
            'amount' => ['required']
        ]);

        $payment = new Paystack();
        $payment = $payment->makePaymentRequest([
            'email' => request('email'),
            'amount' => request('amount'),
            'metadata' => [
                'order_id' => $order->id
            ]
        ]);

        return ApiResponder::successResponse("Payment details", $payment->getResponse()['data']);
    }

    public function payWebhook(Request $request)
    {
        if ((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') || !array_key_exists('HTTP_X_PAYSTACK_SIGNATURE', $_SERVER)) {
            // only a post with paystack signature header gets our attention
            exit();
        }
        // Retrieve the request's body
        $input = @file_get_contents('php://input');
        define('PAYSTACK_SECRET_KEY', config('paystack.secretKey'));
        if (!$_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] || ($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('sha512', $input, PAYSTACK_SECRET_KEY))) {
            // silently forget this ever happened
            exit();
        }
        http_response_code(200);
        // parse event (which is json string) as object
        // Do something — that will not take long — with $event
        $event = json_decode($input);
        // $bank = $event->authorization->receiver_bank;
        $ref = $event->data->reference;
        $email = $event->data->customer->email;
        $amountInKobo = $event->data->amount;
        $status = $event->data->status;
        $paystackFeeInKobo = $event->data->fees;
        $orderId = $event->data->metadata->order_id;

        Log::info($ref . ' ' . $email . ' ' . $amountInKobo);

        $this->transactions->create([
            'order_id' => $orderId,
            'reference' => $ref,
            'amount' => $amountInKobo,
            'status' => $status,
            'paystack_fee' => $paystackFeeInKobo,
        ]);
    }
}

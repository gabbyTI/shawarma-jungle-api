<?php

/*
 * This file is part of the Laravel Paystack package.
 *
 * (c) Prosper Otemuyiwa <prosperotemuyiwa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services;

use App\Exceptions\IsNullException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class Paystack
{
    /**
     * Transaction Verification Successful
     */
    const VS = 'Verification successful';

    /**
     *  Invalid Transaction reference
     */
    const ITF = "Invalid transaction reference";

    /**
     * Issue Secret Key from your Paystack Dashboard
     * @var string
     */
    protected $secretKey;

    /**
     * Instance of Client
     * @var Client
     */
    protected $client;

    /**
     *  Response from requests made to Paystack
     * @var mixed
     */
    protected $response;

    /**
     * Paystack API base Url
     * @var string
     */
    protected $baseUrl;

    /**
     * Authorization Url - Paystack payment page
     * @var string
     */
    protected $authorizationUrl;

    public function __construct()
    {
        $this->setKey();
        $this->setBaseUrl();
        $this->setRequestOptions();
    }

    /**
     * Get Base Url from Paystack config file
     */
    public function setBaseUrl()
    {
        $this->baseUrl = Config::get('paystack.paymentUrl');
    }

    /**
     * Get secret key from Paystack config file
     */
    public function setKey()
    {
        $this->secretKey = Config::get('paystack.secretKey');
    }

    /**
     * Set options for making the Client request
     */
    private function setRequestOptions()
    {
        $authBearer = 'Bearer ' . $this->secretKey;

        $this->client = new Client(
            [
                'base_uri' => $this->baseUrl,
                'headers' => [
                    'Authorization' => $authBearer,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json'
                ]
            ]
        );
    }


    /**
     * Initiate a payment request to Paystack
     * Included the option to pass the payload to this method for situations
     * when the payload is built on the fly (not passed to the controller from a view)
     * @return Paystack
     */

    public function makePaymentRequest($data = null)
    {
        if ($data == null) {

            $quantity = intval(request()->quantity ?? 1);

            $data = array_filter([
                "amount" => intval(request()->amount) * $quantity,
                "reference" => request()->reference,
                "email" => request()->email,
                "plan" => request()->plan,
                "first_name" => request()->first_name,
                "last_name" => request()->last_name,
                "callback_url" => request()->callback_url,
                "currency" => (request()->currency != ""  ? request()->currency : "NGN"),

                /*
                    Paystack allows for transactions to be split into a subaccount -
                    The following lines trap the subaccount ID - as well as the ammount to charge the subaccount (if overriden in the form)
                    both values need to be entered within hidden input fields
                */
                "subaccount" => request()->subaccount,
                "transaction_charge" => request()->transaction_charge,

                /**
                 * Paystack allows for transaction to be split into multi accounts(subaccounts)
                 * The following lines trap the split ID handling the split
                 * More details here: https://paystack.com/docs/payments/multi-split-payments/#using-transaction-splits-with-payments
                 */
                "split_code" => request()->split_code,

                /**
                 * Paystack allows transaction to be split into multi account(subaccounts) on the fly without predefined split
                 * form need an input field: <input type="hidden" name="split" value="{{ json_encode($split) }}" >
                 * array must be set up as:
                 *  $split = [
                 *    "type" => "percentage",
                 *     "currency" => "KES",
                 *     "subaccounts" => [
                 *       { "subaccount" => "ACCT_li4p6kte2dolodo", "share" => 10 },
                 *       { "subaccount" => "ACCT_li4p6kte2dolodo", "share" => 30 },
                 *     ],
                 *     "bearer_type" => "all",
                 *     "main_account_share" => 70,
                 * ]
                 * More details here: https://paystack.com/docs/payments/multi-split-payments/#dynamic-splits
                 */
                "split" => request()->split,
                /*
                * to allow use of metadata on Paystack dashboard and a means to return additional data back to redirect url
                * form need an input field: <input type="hidden" name="metadata" value="{{ json_encode($array) }}" >
                * array must be set up as:
                * $array = [ 'custom_fields' => [
                *                   ['display_name' => "Cart Id", "variable_name" => "cart_id", "value" => "2"],
                *                   ['display_name' => "Sex", "variable_name" => "sex", "value" => "female"],
                *                   .
                *                   .
                *                   .
                *                  ]
                *          ]
                */
                'metadata' => request()->metadata
            ]);
        }

        self::setHttpResponse('/transaction/initialize', 'POST', $data);

        return $this;
    }


    /**
     * @param string $relativeUrl
     * @param string $method
     * @param array $body
     * @return Paystack
     * @throws IsNullException
     */
    private function setHttpResponse($relativeUrl, $method, $body = [])
    {
        if (is_null($method)) {
            throw new IsNullException("Empty method not allowed");
        }

        $this->response = $this->client->{strtolower($method)}(
            $this->baseUrl . $relativeUrl,
            ["body" => json_encode($body)]
        );

        return $this;
    }

    /**
     * Get the whole response from a get operation
     * @return array
     */
    public function getResponse()
    {
        return json_decode($this->response->getBody(), true);
    }

    // /**
    //  * Get the authorization url from the callback response
    //  * @return Paystack
    //  */
    // public function getAuthorizationUrl()
    // {
    //     $this->makePaymentRequest();

    //     $this->url = $this->getResponse()['data']['authorization_url'];

    //     return $this;
    // }

    // /**
    //  * Get the authorization callback response
    //  * In situations where Laravel serves as an backend for a detached UI, the api cannot redirect
    //  * and might need to take different actions based on the success or not of the transaction
    //  * @return array
    //  */
    // public function getAuthorizationResponse($data)
    // {
    //     $this->makePaymentRequest($data);

    //     $this->url = $this->getResponse()['data']['authorization_url'];

    //     return $this->getResponse();
    // }

    // /**
    //  * Hit Paystack Gateway to Verify that the transaction is valid
    //  */
    // private function verifyTransactionAtGateway()
    // {
    //     $transactionRef = request()->query('trxref');

    //     $relativeUrl = "/transaction/verify/{$transactionRef}";

    //     $this->response = $this->client->get($this->baseUrl . $relativeUrl, []);
    // }

    // /**
    //  * True or false condition whether the transaction is verified
    //  * @return boolean
    //  */
    // public function isTransactionVerificationValid()
    // {
    //     $this->verifyTransactionAtGateway();

    //     $result = $this->getResponse()['message'];

    //     switch ($result) {
    //         case self::VS:
    //             $validate = true;
    //             break;
    //         case self::ITF:
    //             $validate = false;
    //             break;
    //         default:
    //             $validate = false;
    //             break;
    //     }

    //     return $validate;
    // }

    // /**
    //  * Get Payment details if the transaction was verified successfully
    //  * @return json
    //  * @throws PaymentVerificationFailedException
    //  */
    // public function getPaymentData()
    // {
    //     if ($this->isTransactionVerificationValid()) {
    //         return $this->getResponse();
    //     } else {
    //         throw new PaymentVerificationFailedException("Invalid Transaction Reference");
    //     }
    // }

    // /**
    //  * Fluent method to redirect to Paystack Payment Page
    //  */
    // public function redirectNow()
    // {
    //     return redirect($this->url);
    // }

    // /**
    //  * Get Access code from transaction callback respose
    //  * @return string
    //  */
    // public function getAccessCode()
    // {
    //     return $this->getResponse()['data']['access_code'];
    // }

    // /**
    //  * Generate a Unique Transaction Reference
    //  * @return string
    //  */
    // public function genTranxRef()
    // {
    //     return TransRef::getHashedToken();
    // }
}

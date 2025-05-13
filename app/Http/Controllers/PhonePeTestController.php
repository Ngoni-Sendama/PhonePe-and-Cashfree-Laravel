<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class PhonePeTestController extends Controller
{
    public function phonePe(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $amountInPaise = (int) ($request->amount * 100); // Convert rupees to paise

        $data = [
            'merchantId' => env('PhonePe_MERCHANT_ID'), // Production merchant ID
            'merchantTransactionId' => uniqid(),
            'merchantUserId' => 'MUID124',
            'amount' => $amountInPaise,
            'redirectUrl' => route('response'),
            'redirectMode' => 'POST',
            'callbackUrl' => route('response'),
            'mobileNumber' => '8264609578',
            'paymentInstrument' => [
                'type' => 'PAY_PAGE',
            ],
        ];

        $encode = base64_encode(json_encode($data));

        $saltKey = env('PhonePe_SALT_KEY'); // Production salt key
        $saltIndex = 1;

        $string = $encode . '/pg/v1/pay' . $saltKey;
        $sha256 = hash('sha256', $string);
        $finalXHeader = $sha256 . '###' . $saltIndex;

        $url = 'https://api.phonepe.com/apis/hermes/pg/v1/pay';

        $response = Curl::to($url)
            ->withHeader('Content-Type: application/json')
            ->withHeader('X-VERIFY:' . $finalXHeader)
            ->withData(json_encode(['request' => $encode]))
            ->post();

        $rData = json_decode($response);

        if (
            isset($rData->success) && $rData->success === true &&
            isset($rData->data->instrumentResponse->redirectInfo->url)
        ) {
            return redirect()->to($rData->data->instrumentResponse->redirectInfo->url);
        } else {
            logger()->error('PhonePe Payment Error', ['response' => $rData]);

            return response()->json([
                'error' => 'Failed to initiate payment',
                'details' => $rData,
            ], 500);
        }
    }

    public function response(Request $request)
    {
        $input = $request->all();

        $saltKey = env('PhonePe_SALT_KEY');
        $saltIndex = 1;

        $finalXHeader = hash('sha256', '/pg/v1/status/' . $input['merchantId'] . '/' . $input['transactionId'] . $saltKey) . '###' . $saltIndex;

        $response = Curl::to('https://api-preprod.phonepe.com/apis/merchant-simulator/pg/v1/status/' . $input['merchantId'] . '/' . $input['transactionId'])
            ->withHeader('Content-Type:application/json')
            ->withHeader('accept:application/json')
            ->withHeader('X-VERIFY:' . $finalXHeader)
            ->withHeader('X-MERCHANT-ID:' . $input['transactionId'])
            ->get();

        dd(json_decode($response));
    }
}

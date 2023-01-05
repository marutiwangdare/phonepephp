<?php 
//composer require guzzlehttp/guzzle

require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$data = [
    "merchantId"=> "PGTESTPAYUAT",
    "merchantTransactionId" => '45789879585',
    "merchantUserId" => "MUID47584987986",
    "amount" => 100,
    "redirectUrl" => "http://localhost/phonepe/redirect.php",
    "redirectMode" => "POST",
    "callbackUrl" => "http://localhost/phonepe/callback.php",
    "mobileNumber" => "9068145151",
    "paymentInstrument" => [
      "type" => "PAY_PAGE"
    ]
];

$body = base64_encode(json_encode($data));
$APIEndpoint = "/pg/v1/pay";
$SaltKey = "099eb0cd-02cf-4e2a-8aca-3e6c6aff0399";
$SaltIndex = 1;

$raw =$body."/pg/v1/pay".$SaltKey;


$XVERIFY = hash('sha256', $raw)."###".$SaltIndex;

  $response = $client->request('POST', 'https://api-preprod.phonepe.com/apis/hermes/pg/v1/pay', [
    'body' => '{"request":"'.$body.'"}',
    'headers' => [
      'Content-Type' => 'application/json',
      'X-VERIFY' => $XVERIFY,
      'accept' => 'application/json',
    ],
  ]);
  

$result=json_decode($response->getBody(), true);

print_r($result);

if($result['success']==1){
    $url = $result['data']['instrumentResponse']['redirectInfo']['url'];
    header("Location: $url");
    die();
}


/*
{
  "success": true,
  "code": "PAYMENT_INITIATED",
  "message": "Payment initiated",
  "data": {
    "merchantId": "PGTESTPAYUAT",
    "merchantTransactionId": "phone123",
    "instrumentResponse": {
      "type": "PAY_PAGE",
      "redirectInfo": {
        "url": "https://mercury-uat.phonepe.com/transact/pg?token=OGYyNzkzZjAyMTRlNWUzNTgzMTRmNmQ3ZTM2MDI2MWVjZDU5M2I0N2Q5ZWU5ZTExYWQ2MzlhYjFhNTI0NTAyMmIxYjU4Zjk0YmQ6YzE3YzIxYWJmYmNiNzZkYTViOGJhNmIwYWZlZDI0ZGE",
        "method": "GET"
      }
    }
  }


  Test credentials:

 

MID: PGTESTPAYUAT

Key Index: 1, 

Key: 099eb0cd-02cf-4e2a-8aca-3e6c6aff0399

 

Test card details:

 

"card_number": "4622943126146407", 

"card_type": "DEBIT_CARD", 

"card_issuer": "VISA",

"expiry_month": 12, 

"expiry_year": 2023, 

"cvv": "936",

 

Bank Page OTP: 123456
*/
?>
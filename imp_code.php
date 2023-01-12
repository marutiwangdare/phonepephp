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

Array ( [code] => PAYMENT_ERROR [merchantId] => PGTESTPAYUAT [transactionId] => MTID3620230108095646 [amount] => 23200 [param1] => na [param2] => na [param3] => na [param4] => na [param5] => na [param6] => na [param7] => na [param8] => na [param9] => na [param10] => na [param11] => na [param12] => na [param13] => na [param14] => na [param15] => na [param16] => na [param17] => na [param18] => na [param19] => na [param20] => na [checksum] => 0e865dd0ccba1ba2527a79ad4422fc065ae6a282a938a1bab5860e953c3fc430###1 )

Array ( [code] => PAYMENT_SUCCESS [merchantId] => PGTESTPAYUAT [transactionId] => MTID839437644220230111045755 [amount] => 100 [providerReferenceId] => T2301111658151412377217 [param1] => na [param2] => na [param3] => na [param4] => na [param5] => na [param6] => na [param7] => na [param8] => na [param9] => na [param10] => na [param11] => na [param12] => na [param13] => na [param14] => na [param15] => na [param16] => na [param17] => na [param18] => na [param19] => na [param20] => na [checksum] => 8d6f80372ce556de7c3c7e43735261bb73363541074066e8ae5f99513e4c8940###1 )

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


25942c70e3cc1576a90558e053ab20203de9d771430933b8d21c232b70244e36###1


// key which will sign the data 
$key = hash('sha256', '1');

// your data
$array = [
    'foobar' => 'baz'    
];

// encode the payload
$json = json_encode($array);

// sign it with key
$token = hash_hmac('sha256', $json, $key);


$check = hash_hmac('sha256', $json , $key);

if (hash_equals($token, $check)) {
    echo 'Verified';
} else {
    echo 'Tampered';
}

*/
?>
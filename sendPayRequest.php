<?php 
//composer require guzzlehttp/guzzle
require_once('vendor/autoload.php');
// Include configuration file 
include_once 'config.php'; 

$product_id = $_POST['product_id'];  
$amount = $_POST['amount'];
$amount_in_paisa = $amount *100; //convert to paisa
$user_id=1;



$insert = $db->query("INSERT INTO payments(user_id,product_id,amount) VALUES('".$user_id."','".$product_id."','".$amount."')"); 
$payment_id = $db->insert_id; 

$merchantTransactionId = 'MTID'.$payment_id.date("Ymdhis"); 

$data = [
    "merchantId"=> merchantId,
    "merchantTransactionId" => $merchantTransactionId,
    "merchantUserId" => $user_id,
    "amount" => $amount_in_paisa,
    "redirectUrl" => redirectUrl,
    "redirectMode" => "POST",
    "callbackUrl" => callbackUrl,
    "mobileNumber" => mobileNumber,
    "paymentInstrument" => [
      "type" => "PAY_PAGE"
    ]
];

$body = base64_encode(json_encode($data));

$raw =$body.apiEndpoint.saltKey;


$XVERIFY = hash('sha256', $raw)."###".saltIndex;

$client = new \GuzzleHttp\Client();
$response = $client->request('POST', payApiUrl, [
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

    $update = $db->query("UPDATE payments SET merchant_transaction_id='$merchantTransactionId', payment_code='$result[code]' WHERE id=$payment_id"); 

    $url = $result['data']['instrumentResponse']['redirectInfo']['url'];
    header("Location: $url");
    die();
}else{
    echo $result['message'];
}
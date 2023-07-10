<?php 
//composer require guzzlehttp/guzzle
require_once('vendor/autoload.php');
// Include configuration file 
include_once 'config.php'; 

print_r($_POST);

$raw = "/pg/v1/status/".merchantId."/".$_SESSION["merchantTransactionId"]."".saltKey;


$XVERIFY = hash('sha256', $raw)."###".saltIndex;

$client = new \GuzzleHttp\Client();
$response = $client->request('GET', statusApiUrl.$_SESSION["merchantTransactionId"], [
    'headers' => [
      'Content-Type' => 'application/json',
      'X-VERIFY' => $XVERIFY,
      'X-MERCHANT-ID' => merchantId,
    ],
  ]);
  

$response=json_decode($response->getBody(), true);

print_r($response);

$rawResponse = json_encode($response);
$code = $response['code']; 
$amount = $response['data']['amount']/100; 

$merchantTransactionId = $response['data']['merchantTransactionId']; 
$providerReferenceId = $response['data']['transactionId']; 

if($code == 'PAYMENT_SUCCESS')
{
   
    $prevPaymentResult = $db->query("SELECT * FROM payments WHERE merchant_transaction_id = '".$merchantTransactionId."'"); 
 
    $update = $db->query("UPDATE payments SET provider_reference_id='$providerReferenceId', payment_code='$code', raw_response='$rawResponse'  WHERE merchant_transaction_id='$merchantTransactionId'"); 

    
}else{
    $update = $db->query("UPDATE payments SET provider_reference_id='$providerReferenceId',payment_code='$code', raw_response='$rawResponse'  WHERE merchant_transaction_id='$merchantTransactionId'"); 
}
?>

<div class="container">
    <div class="status">
        <?php if($code == 'PAYMENT_SUCCESS'){ ?>
            <h1 class="success"><?php echo $code?></h1>
			
            <h4>Payment Information</h4>
            <p><b>providerReferenceId:</b> <?php echo $providerReferenceId; ?></p>
            <p><b>Paid Amount:</b> <?php echo $amount; ?></p>
            <p><b>Payment Status:</b> <?php echo $code; ?></p>
			
        <?php }else{ ?>
            <h1 class="error"><?php echo $code?></h1>
        <?php } ?>
    </div>
    <a href="index.php" class="btn-link">Back to Products</a>
</div>
<!--
    Array ( [success] => 1 [code] => PAYMENT_SUCCESS [message] => Your payment is successful. [data] => Array ( [merchantId] => PGTESTPAYUAT [merchantTransactionId] => MTID3820230524014211 [transactionId] => T2305241712136435449446 [amount] => 10000 [state] => COMPLETED [responseCode] => SUCCESS [paymentInstrument] => Array ( [type] => NETBANKING [pgTransactionId] => 1995464773 [pgServiceTransactionId] => PG2212291607083344934300 [bankTransactionId] => [bankId] => null ) ) )
-->
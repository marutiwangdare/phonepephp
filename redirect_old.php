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
  

$result=json_decode($response->getBody(), true);

print_r($result);

$rawResponse = json_encode($_POST);
$code = $_POST['code']; 
$amount = $_POST['amount']/100; 

$merchantTransactionId = $_POST['transactionId']; 
$providerReferenceId = $_POST['providerReferenceId']; 

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
    Array ( 
        [code] => PAYMENT_ERROR 
        [merchantId] => PGTESTPAYUAT 
        [transactionId] => MTID839437644220230112020515 
        [amount] => 100 
        [param1] => na [param2] => na [param3] => na [param4] => na [param5] => na [param6] => na [param7] => na [param8] => na [param9] => na [param10] => na [param11] => na [param12] => na [param13] => na [param14] => na [param15] => na [param16] => na [param17] => na [param18] => na [param19] => na [param20] => na 
        [checksum] => eb4b7ba07a93aaa3708b67f63f0553e463e79f34272e3b001226b0d910f36a5b###1 )
-->
<?php 
// Include configuration file 
include_once 'config.php'; 

print_r($_POST);

$code = $_POST['code'] ; 
$rawResponse = json_encode($_POST);
$code = $_POST['code']; 
$amount = $_POST['amount']/100; 
$message = '';

if($code == 'PAYMENT_SUCCESS')
{
    $message = $_POST['message']; 
    $merchantTransactionId = $_POST['data']['merchantTransactionId']; 
    $transactionId = $_POST['data']['transactionId']; 

    $prevPaymentResult = $db->query("SELECT * FROM payments WHERE merchant_transaction_id = '".$merchantTransactionId."'"); 
 
    if($prevPaymentResult->num_rows > 0){ 
        $paymentRow = $prevPaymentResult->fetch_assoc(); 
       // $amount = $paymentRow['amount']; 

        $update = $db->query("UPDATE payments SET transactionId='$transactionId', payment_code='$code', raw_response='$rawResponse'  WHERE merchant_transaction_id=$merchantTransactionId"); 

    }
}else{
    
    $merchantTransactionId = $_POST['transactionId']; 
    $update = $db->query("UPDATE payments SET , payment_code='$code', raw_response='$rawResponse'  WHERE merchant_transaction_id=$merchantTransactionId"); 
}
?>

<div class="container">
    <div class="status">
        <?php if($code == 'PAYMENT_SUCCESS'){ ?>
            <h1 class="success"><?php echo $message?></h1>
			
            <h4>Payment Information</h4>
            <p><b>Reference Number:</b> <?php echo $payment_id; ?></p>
            <p><b>Transaction ID:</b> <?php echo $transactionId; ?></p>
            <p><b>Paid Amount:</b> <?php echo $amount; ?></p>
            <p><b>Payment Status:</b> <?php echo $code; ?></p>
			
        <?php }else{ ?>
            <h1 class="error"><?php echo $message?></h1>
        <?php } ?>
    </div>
    <a href="index.php" class="btn-link">Back to Products</a>
</div>
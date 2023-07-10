<?php 
session_start();
/* 
 * phonepe and database configuration 
 */ 
  
// phonepe configuration 
define('merchantId', 'PGTESTPAYUAT'); 
define('redirectUrl', 'http://localhost/phonepephp/redirect.php'); 
define('callbackUrl', 'http://localhost/phonepephp/callback.php'); 
define('mobileNumber', '9068145151');
define('apiEndpoint', '/pg/v1/pay');
define('saltKey', '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399');
define('saltIndex', '1');
define('payApiUrl', 'https://api-preprod.phonepe.com/apis/hermes/pg/v1/pay');
//define('payApiUrl', 'https://api-preprod.phonepe.com/apis/merchant-simulator/pg/v1/pay');
define('statusApiUrl', 'https://api-preprod.phonepe.com/apis/merchant-simulator/pg/v1/status/'.merchantId.'/');




// Database configuration 
define('DB_HOST', 'localhost'); 
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', ''); 
define('DB_NAME', 'phonepe'); 
 
// Connect with the database 
$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME); 
 
// Display error if failed to connect 
if ($db->connect_errno) { 
    printf("Connect failed: %s\n", $db->connect_error); 
    exit(); 
}
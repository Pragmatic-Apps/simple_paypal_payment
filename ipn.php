<?php
$sandbox = true; // change to false in production
/*
 * USE THIS SCRIPT TO CREATE A SIMPLA PAYPAL PAYMENT REQUEST
 *  
 * Use this script to handle the paypal IPN feedback 
 * More about IPN handling here: https://developer.paypal.com/docs/classic/ipn/ht_ipn/
 * 
 * created by pragmatic_apps <info@pragmatic-apps.de>
 */

// 1) READ THE IPN DATA
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$resultData = array();
foreach ($raw_post_array as $keyval) {
  $keyval = explode ('=', $keyval);
  if (count($keyval) == 2)
    $resultData[$keyval[0]] = urldecode($keyval[1]);
}
// PREPEND 'cmd=_notify-validate' to validate
$req = 'cmd=_notify-validate';
if (function_exists('get_magic_quotes_gpc')) {
  $get_magic_quotes_exists = true;
}
foreach ($resultData as $key => $value) {
  if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
    $value = urlencode(stripslashes($value));
  } else {
    $value = urlencode($value);
  }
  $req .= "&$key=$value";
}

// 2) POST IPN DATA BACK TO PAYPAL TO VALIDATE
$url = ($sandbox) ? 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr' : 'https://ipnpb.paypal.com/cgi-bin/webscr';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
if ( !($res = curl_exec($ch)) ) {
  curl_close($ch);
  exit;
}
curl_close($ch);

// 3) PROCESS VERIFICATION RESULT
if (strcmp ($res, "VERIFIED") == 0) {
    $status = $_POST['payment_status']; // the status of the payment. If everything is fine it should be 'completed' else its mostly 'pending' then you can check the pending_reason parameter
    $order_id = $_POST['txn_id']; // the order id defined in the send_payment.php file

    /**
     * YOUR LOGIC TO 
     * - SAVE THE PAYMENT STATUS e.g. UPDATE orders SET status = $status WHERE id = $order_id;
     * - SEND CUSTOMER MAIL (its a good idea to track if mailw as already sent as it may happen that IPN triggers more than once)
     */
} else if (strcmp ($res, "INVALID") == 0) {

    // SOMETHING WENT WROT PAYPAL RESULT WAS INVALID

    /*
     * YOUR ERROR HANDLING HERE
     */
}

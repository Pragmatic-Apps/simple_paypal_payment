<?php

/*
 * USE THIS SCRIPT TO CREATE A SIMPLA PAYPAL PAYMENT REQUEST
 *  
 * Usage: Simply insert your variables below and extend the $paymentQuery with your cart information
 * More about the used variables here: https://developer.paypal.com/docs/classic/paypal-payments-standard/integration-guide/Appx_websitestandard_htmlvariables
 * 
 * created by pragmatic_apps <info@pragmatic-apps.de>
 */


#
#  CONFIGURATION
#
$_sandbox = true; // Use false for production
$_paypal_account = "xx@yy.de"; // verified paypal account mail
$_ipn_callback_url = "https://www.example.de/ipn.php"; // Url for paypal callback to verify transaction (other script)
$_return_url = "https://www.example.de/success.html"; // Where to go if payment was successfull
$_cancel_url = "https://www.example.de/cart.html"; // Where to go if user aborts payment
$_image_url = "https://www.example.de/logo.jpg"; // Logo displayed on the paypal checkout page (150x150)

#
#  YOUR LOGIC e.g. add the order to a databse => status "open" => we need the id of this order for later processing
#
$_order_id = 123;



#
#  Payment Process
#
$_payment_url = ($_sandbox) ? 'https://www.sandbox.paypal.com/webscr?' : 'https://www.sandbox.paypal.com/webscr?';

// CONFIG BASIC SETTINGS
$paymentQuery['business']      = $_paypal_account;
$paymentQuery['return']      = $_return_url;
$paymentQuery['cancel_return']      = $_cancel_url;
$paymentQuery['notify_url']    = $_ipn_callback_url;
$paymentQuery['upload']    = 1;
$paymentQuery['cmd']      = "_cart";
$paymentQuery['currency_code'] = "EUR";
$paymentQuery['invoice'] = _order_id;

// ADDRESS SETTING
$paymentQuery['address_override'] = 1;
$paymentQuery['first_name'] = "Max";
$paymentQuery['last_name']  = "Muster";
$paymentQuery['address1']  = "Musterstrasse 12";
$paymentQuery['address2']  = "Postfach 123"; // OPTIONAL
$paymentQuery['zip']  = "12345";
$paymentQuery['city']  = "Musterstadt";
$paymentQuery['country']  = "DE"; // 2 character ISO-Code
$paymentQuery['email']      = "muster@muster.de";

// PRODUCT SETTING
$paymentQuery['item_name_1'] = "Cool Product"; // PRODUCT NAME
$paymentQuery['quantity_1'] = 2; // QUANTATIY
$paymentQuery['amount_1'] = number_format(99.99,2,'.',''); // PRODUCT PRICE FOR A SINGLE PRODUCT

$paymentQuery['item_name_2'] = "Another Cool Product "; // PRODUCT NAME
$paymentQuery['quantity_2'] = 1; // QUANTATIY
$paymentQuery['amount_2'] = number_format(19.99,2,'.',''); // PRODUCT PRICE FOR A SINGLE PRODUCT
# [REPEAT FOR AS MANY PRODUCTS AS NECESSARY]

// TOTALS
//$paymentQuery['handling'] = number_format(6.90,2,'.',''); // OPTIONAL: SHIPPING COSTS IF NECESSARY
//$paymentQuery['tax_cart'] = number_format(6.90,2,'.',''); // OPTIONAL: TOTAL TAX AMOUNT
//$paymentQuery['discount_amount_cart'] = number_format(6.90,2,'.',''); // OPTIONAL: DISCOUNT AMOUNT
$paymentQuery['mc_gross'] = number_format(219.97,2,'.',''); // TOTAL AMOUNT 


// GOTO PAYPAL
$paymentQueryString = http_build_query($paymentQuery);
header('Location: ' . _payment_url . $paymentQueryString);


<?php

// Different shop for production
if (ENVIRONMENT == 'production')
{
	//$config['payment_shop_id'] 		= 10000697;
	//$config['payment_secret_key'] 	= '';
	$config['payment_shop_id'] 		= 10000006;
	$config['payment_secret_key'] 	= 'test3d';

}
else
{
	$config['payment_shop_id'] 		= 10000006;
	$config['payment_secret_key'] 	= 'test3d';
}

$config['payment_encrypt_key']	     = 'chif9ny2mo1whob9wy9ga4vy';
$config['payment_shop_lang'] 	     = 'HR';
$config['payment_type'] 		     = 'manual';
$config['payment_cc_test'] 		     = '377500519401008'; // Credit card number for testing
$config['payment_cvv2_test'] 	     = '551'; // CVV2 code on test credit card
$config['payment_send_confirmation'] = FALSE;

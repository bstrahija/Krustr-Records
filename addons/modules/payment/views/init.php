<!doctype html>  

<html lang="en" class="no-js">
<head>
	<meta charset="utf-8">
	<title>PG Test</title>
</head>
<body>

<form action="https://pgw.t-com.hr/payment.aspx" method="POST" style="visibility: hidden;" name="paymentform">
	<?php
		// Check the discount code
		$amount = $cart->amount;
		if ($cart->discount_code) {
			$discount_code = $this->discount_code_m->get_by('code', $cart->discount_code);
			if ($discount_code and ! $discount_code->slots)            $discount_code = null;
			if ($discount_code and $discount_code->expires_at < now()) $discount_code = null;
			if ($discount_code->slots) {
				$amount = $amount - round($amount * ($discount_code->discount / 100), 2);
			} // end if
		} // end if
		
		// Rest of data
		$ShopID 		= $this->config->item('payment_shop_id');
		$SecretKey 		= $this->config->item('payment_secret_key');
		$ShoppingCartId = $cart->track_id;
		
		if (ENVIRONMENT == 'production') {
			$TotalAmount 	= number_format((float) $amount, 2, ",", ".");
		}
		else {
			$TotalAmount 	= '10,00';
		} // end if
		
		$md5_signature 	= md5($ShopID.$SecretKey.$ShoppingCartId.$SecretKey.$TotalAmount.$SecretKey);
		
		// Build the return URL
		$return_url        = 'payment/response/'.$cart->track_id.'/';
		$return_secret_key = str_replace(array('=', '+', '/', "\\"), array('_', '-', ':', '%'), $cart->secret_key);
		$return_url        = $return_url.$return_secret_key;
		
		// Updatethe sig
		$this->cart_m->update_by(array('track_id'=>$cart->track_id), array('pg_sig'=>$md5_signature));
		
		$field_type = 'text';
	?>
	<input type="<?php echo $field_type; ?>" name="ShopID" 				value="<?php echo $ShopID; ?>"><br>
	<input type="<?php echo $field_type; ?>" name="ShoppingCartID" 		value="<?php echo $ShoppingCartId; ?>"><br>
	<input type="<?php echo $field_type; ?>" name="TotalAmount" 		value="<?php echo $TotalAmount; ?>"><br>
	<input type="<?php echo $field_type; ?>" name="ReturnURL" 			value="<?php echo url($return_url); ?>" size="300"><br>
	<input type="<?php echo $field_type; ?>" name="CancelURL" 			value="<?php echo url_https('kupi') ?>"><br>
	<input type="<?php echo $field_type; ?>" name="SecretKey" 			value="<?php echo $SecretKey; ?>"><br>
	<input type="<?php echo $field_type; ?>" name="Signature" 			value="<?php echo $md5_signature; ?>"><br>
	<input type="<?php echo $field_type; ?>" name="Lang" 				value="<?php echo $this->config->item('payment_shop_lang'); ?>"><br>
	<input type="<?php echo $field_type; ?>" name="Curr" 				value="y"><br>
	<input type="<?php echo $field_type; ?>" name="PaymentType" 		value="<?php echo $this->config->item('payment_type'); ?>"><br>
	<input type="<?php echo $field_type; ?>" name="Installments" 		value="y"><br>
	<input type="<?php echo $field_type; ?>" name="CustomerFirstname" 	value="<?php echo user_var('first_name'); ?>"><br>
	<input type="<?php echo $field_type; ?>" name="CustomerSurname" 	value="<?php echo user_var('last_name'); ?>"><br>
	<input type="<?php echo $field_type; ?>" name="CustomerAddress" 	value="<?php echo user_var('address'); ?>"><br>
	<input type="<?php echo $field_type; ?>" name="CustomerCity" 		value="<?php echo user_var('city'); ?>"><br>
	<input type="<?php echo $field_type; ?>" name="CustomerZIP" 		value="<?php echo user_var('postal_code'); ?>"><br>
	<input type="<?php echo $field_type; ?>" name="CustomerCountry" 	value="<?php echo user_var('country'); ?>"><br>
	<input type="<?php echo $field_type; ?>" name="CustomerPhone" 		value="<?php echo user_var('phone'); ?>"><br>
	<input type="<?php echo $field_type; ?>" name="CustomerEmail" 		value="<?php echo user_email(); ?>"><br>
	<input type="submit" value="Kupi">
</form>

<script>
window.onload = function() { document.forms['paymentform'].submit(); };
</script>


<?php
	// Test the data
	/*$track_id 		= $cart->track_id;
	$secret_key 	= $this->cart->decrypt_shop_secret_key($return_secret_key);
	$secret_data 	= explode('_', $secret_key);
	
	echo '<pre>'; print_r($track_id); echo '</pre>';
	echo '<pre>'; print_r($secret_key); echo '</pre>';
	echo '<pre>'; print_r($secret_data); echo '</pre>';
	die();*/
?>


</body>
</html>
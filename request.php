<?php

$order_id = 1000;

$merchant_id = ""; //Your Merchant Id
$merchant_secret = ""; //Your Merchant Secret
$amount = 300;

$success_hash = hash_hmac('sha256', $merchant_id . sprintf("%.02f", round($amount, 2)) . $order_id, $merchant_secret);
$fail_hash = hash_hmac('sha256', $order_id, $merchant_secret);


$redirect_url = ""; // Return Url With Transaction ID
$notify_url = ""; // Return Url With Transaction ID

$success_url = $notify_url . '&hash=' . base64_encode($success_hash);
$fail_url = $notify_url . '&hash=' . base64_encode($fail_hash);


$api_url = "https://app.mintpay.lk/user-order/api/";
$form_url = "https://app.mintpay.lk/user-order/login/";

//List Doen Your Shopping Cart Items Here
$order_items[] = array(
	'name' => "Test",
	'product_id' => 115,
	'sku' => "Skirts",
	'quantity' => 1,
	'unit_price' => 300,
	'created_date' => "2001-10-01 01:10:01",
	'updated_date' => "2001-10-01 01:10:01",
	'discount' => "0.00"
);


$postData = [
	'merchant_id' => $merchant_id,
	'order_id' => $order_id,
	'total_price' => $amount,
	'discount' => 0,
	'customer_id' => 322,
	'customer_email' => "",
	'customer_telephone' => "",
	'ip' => "192.168.0.32",
	'x_forwarded_for' => "192.168.0.32",
	'delivery_street' => "",
	'delivery_region' => "",
	'delivery_postcode' => "",
	'cart_created_date' => date("Y-m-d H:i:s"),
	'cart_updated_date' => date("Y-m-d H:i:s"),
	'success_url' => $success_url,
	'fail_url' => $fail_url,
	'products' => $order_items,
];



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData)); //Post Fields
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
	'Authorization: Token ' . $merchant_secret,
	'Content-Type: application/json',
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$server_output = curl_exec($ch);
$mintpayRequestData = json_decode($server_output, true);

curl_close($ch);

if (isset($mintpayRequestData['message']) && $mintpayRequestData['message'] == 'Success') {

	echo '<form action="' . $form_url . '"method="post" id="mintpay_payment_form">
			<input type="hidden" name="purchase_id" value="' . $mintpayRequestData['data'] . '" >
			<input type="submit" class="button-alt"  />

			</form>
			<script type="text/javascript">
        document.forms[0].submit()
        document.getElementsByTagName("BODY")[0].style.display = "none";
        </script>';

}

?>
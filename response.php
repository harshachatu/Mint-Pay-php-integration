<?php

$merchant_id = "";
$merchant_secret = "";
$transaction_id = $_GET['id'];
$hash = $_GET['hash'];

if (isset($transaction_id) && is_numeric($transaction_id) && isset($hash)) {

    //Cross check with the database with payment id
    $gettras = "";



    if (sizeof($gettras) > 0 && $gettras['trstatus'] == 0) {


        $amount = $gettras['payment_amount'];

        $success_hash = hash_hmac('sha256', $merchant_id . sprintf("%.02f", round($amount, 2)) . $transaction_id, $merchant_secret);
        $fail_hash = hash_hmac('sha256', $order_id, $merchant_secret);

        if (base64_decode($hash) == $success_hash) {

            //Success


        } else if (base64_decode($hash) == $fail_hash) {
            //Error

        } else {
            //Error
        }

    } else {
        //Error
    }


}

?>
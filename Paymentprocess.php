<?php
// Include the Stripe PHP library
require_once('vendor/autoload.php'); // Make sure the Stripe PHP SDK is installed

// Set your secret key (Get from your Stripe Dashboard)
\Stripe\Stripe::setApiKey('your-secret-key-here'); // Replace with your actual secret key

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the token and amount from the form
    $token = $_POST['stripeToken'];
    $amount = $_POST['amount'] * 100; // Convert dollars to cents

    try {
        // Create the charge
        $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => 'usd', // You can change the currency as needed
            'source' => $token,
            'description' => 'Payment for Membership', // Customize the description
        ]);

        // Check if payment was successful
        if ($charge->status == 'succeeded') {
            echo 'Payment Successful!';
        } else {
            echo 'Payment Failed!';
        }
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Handle any errors that occur during the transaction
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>

<h2>Payment Form</h2>
<form id="payment-form" action="process_payment.php" method="POST">
    <div>
        <label for="amount">Amount (USD):</label>
        <input type="number" name="amount" id="amount" required>
    </div>
    <div id="card-element">
        <!-- A Stripe Element will be inserted here. -->
    </div>

    <!-- Used to display form errors. -->
    <div id="card-errors" role="alert"></div>

    <button type="submit">Pay Now</button>
</form>

<script>
    var stripe = Stripe('your-publishable-key-here'); // Replace with your actual publishable key
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Send the token to your server.
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', result.token.id);
                form.appendChild(hiddenInput);

                // Submit the form
                form.submit();
            }
        });
    });
</script>

</body>
</html>

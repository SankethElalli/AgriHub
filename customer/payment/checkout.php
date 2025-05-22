<?php
require_once 'config.php';
session_start();

// Get amount from session and convert to USD
$amount_inr = isset($_SESSION['amount']) ? $_SESSION['amount'] : 0;
$amount_usd = round($amount_inr * INR_TO_USD_RATE, 2);

// Redirect if no amount set
if($amount_inr <= 0) {
    header("Location: ../cbuy_crops.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PayPal Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../assets/css/creativetim.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h4>Amount to Pay:</h4>
                        <p>₹<?php echo number_format($amount_inr, 2); ?> INR</p>
                        <p>(US $<?php echo number_format($amount_usd, 2); ?>)</p>
                        <div id="paypal-button-container"></div>
                        <div id="error-message" class="alert alert-danger mt-3" style="display: none;"></div>
                        <a href="../cbuy_crops.php" class="btn btn-link mt-3">Cancel Payment</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo PAYPAL_CLIENT_ID; ?>&currency=<?php echo PAYPAL_CURRENCY; ?>"></script>
    <script>
        // Show error message
        function showError(message) {
            const errorDiv = document.getElementById('error-message');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }

        paypal.Buttons({
            style: {
                layout: 'vertical',
                color:  'gold',
                shape:  'rect',
                label:  'paypal'
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $amount_usd; ?>',
                            currency_code: '<?php echo PAYPAL_CURRENCY; ?>'
                        },
                        description: 'AgriHub Purchase'
                    }]
                }).catch(function(err) {
                    console.error('Create order error:', err);
                    showError('Failed to create payment. Please try again.');
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture()
                    .then(function(details) {
                        fetch('process_payment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                orderID: data.orderID,
                                details: details
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                window.location.href = '../cupdatedb.php';
                            } else {
                                showError(data.error || 'Payment processing failed');
                            }
                        })
                        .catch(err => {
                            console.error('Payment processing error:', err);
                            showError('Payment processing failed. Please try again.');
                        });
                    })
                    .catch(function(err) {
                        console.error('Capture order error:', err);
                        showError('Payment capture failed. Please try again.');
                    });
            },
            onError: function(err) {
                console.error('PayPal error:', err);
                showError('PayPal encountered an error. Please try again.');
            },
            onCancel: function() {
                showError('Payment was cancelled. Please try again if you wish to complete the purchase.');
            }
        }).render('#paypal-button-container')
          .catch(function(err) {
              console.error('Render error:', err);
              showError('Failed to load PayPal. Please refresh the page.');
          });
    </script>
</body>
</html>

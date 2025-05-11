<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <link rel="stylesheet" href="../../assets/css/creativetim.min.css">
    <link rel="stylesheet" href="../../assets/css/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center" style="padding: 40px;">
                        <div class="icon-circle mb-4" style="margin: 0 auto; width: 80px; height: 80px;">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <h2 class="display-4 mb-3" style="color: #008641; font-size: 2rem;">Payment Successful!</h2>
                        <p class="lead mb-4">Your payment has been processed successfully.</p>
                        <a href="../dashboard.php" class="btn-farmer" style="text-decoration: none;">
                            <i class="fas fa-home mr-2"></i> Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
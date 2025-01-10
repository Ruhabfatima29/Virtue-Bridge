<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Success</title>
    <link rel="stylesheet" href="css/stripe.css">
</head>
<body>
    <div class="container">
        <h2 style="text-align: center; color: blue;">Stripe Payment Gateway Integration in PHP</h2>
        <h4 style="text-align: center;">Thank you for your donation!</h4>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="status">
                    <h1 style="color: green;">Payment Successful</h1>
                    <h4>Payment Information:</h4>
                    <br>
                    <p><b>Reference ID:</b> <?php echo htmlspecialchars($_GET['order_id'] ?? 'N/A'); ?></p>
                    <p><b>Transaction ID:</b> <?php echo htmlspecialchars($_GET['transaction_id'] ?? 'N/A'); ?></p>
                    <p><b>Paid Amount:</b> $<?php echo htmlspecialchars($_GET['amount'] ?? 'N/A'); ?> USD</p>
                    <p><b>Payment Status:</b> Successful</p>
                </div>
                <a href="index.php" class="btn-continue">Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>

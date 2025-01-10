<?php
declare(strict_types=1);
include "../config/db.php";
session_start();

// Enable detailed error reporting
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', '1');

// Validate and sanitize input
$token = $_POST['stripeToken'] ?? null;
if (!$token) {
    echo json_encode(['success' => false, 'message' => 'Stripe token not received.']);
    exit;
}

if (!empty($_POST['stripeToken'])) {
    require_once('stripe-php/init.php');
    \Stripe\Stripe::setApiKey('sk_test_51QVUmnDf49KbCVBO8JGKg3UMR7kEpnl0zkydD3H4IZqfaKkmfUukt8CkHdGyUI0lzhCJvtnowqY4XfTTUNUbxP7200PxoCIzec');

    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $amount = floatval($_POST['amount'] ?? 0);
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $address = htmlspecialchars(trim($_POST['address'] ?? ''));
    $campaign_id = htmlspecialchars($_POST['campaignId'] ?? '');

    try {
        // Create a Stripe customer
        $customer = \Stripe\Customer::create([
            'email' => $email,
            'source' => $token,
            'name' => $name,
            'description' => 'Donation to Virtue Bridge'
        ]);

        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $charge = \Stripe\Charge::create([
            'customer' => $customer->id,
            'amount' => $amount * 100, // Amount in cents
            'currency' => 'usd',
            'description' => 'Donation',
            'metadata' => ['order_id' => $orderID]
        ]);

        $chargeJson = $charge->jsonSerialize();

        if ($chargeJson['paid'] == 1) {
            try {
                // Start transaction
                $conn->begin_transaction();

                // Insert into donors table
                $stmt_donor = $conn->prepare("INSERT INTO donors (email, phone_number, address) VALUES (?, ?, ?)");
                $stmt_donor->bind_param("sss", $email, $phone, $address);
                $stmt_donor->execute();
                $donor_id = $conn->insert_id;

                // Insert into donations table
                $stmt_donation = $conn->prepare("INSERT INTO donations (campaign_id, donor_table_id, amount, donation_date) VALUES (?, ?, ?, ?)");
                $donation_date = date("Y-m-d H:i:s");
                $stmt_donation->bind_param("iisd", $campaign_id, $donor_id, $amount, $donation_date);
                $stmt_donation->execute();

                // Commit the transaction
                $conn->commit();

                // Store transaction details in session
                $_SESSION['order_id'] = $orderID;
                $_SESSION['transaction_id'] = $chargeJson['balance_transaction'];
                $_SESSION['amount'] = $chargeJson['amount'] / 100;
                $_SESSION['payment_status'] = 'Successful';

                echo json_encode(['success' => true, 'message' => 'Payment successful!']);
            } catch (Exception $e) {
                // Rollback the transaction on error
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
            } finally {
                $stmt_donor->close();
                $stmt_donation->close();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Payment failed.']);
        }
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo json_encode(['success' => false, 'message' => 'Stripe API Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid token or form submission.']);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Success</title>
    <style>
        /* General Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f9fafc;
            color: #333;
        }

        /* Header Section */
        .donation-header {
            background-color: #006d77;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .donation-header__title {
            font-size: 2.8rem;
            margin: 0;
        }

        .donation-header__subtitle {
            font-size: 1.2rem;
            margin: 10px 0 0 0;
            color: #e0f7f9;
        }

        .container {
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            font-size: 24px;
            color: #004aad;
            margin-bottom: 15px;
        }

        h4 {
            font-size: 18px;
            color: #333;
            margin-bottom: 30px;
        }

        .status h1 {
            font-size: 28px;
            color: #28a745;
            margin-bottom: 20px;
        }

        .status h4 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #555;
        }

        .status p {
            font-size: 16px;
            line-height: 1.6;
            margin: 10px 0;
        }

        .status p b {
            color: #004aad;
        }

        .btn-continue {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            font-size: 16px;
            color: #fff;
            background-color: #004aad;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-continue:hover {
            background-color: #003782;
            transform: translateY(-3px);
        }

        .btn-continue:active {
            transform: translateY(1px);
        }
    </style>
</head>

<body>
    <header class="donation-header">
        <h1 class="donation-header__title">Donation Successful</h1>
        <p class="donation-header__subtitle">Thank you for your generosity!</p>
    </header>
    <div class="container">
        <div class="status">
            <h1>Payment Successful</h1>
            <h4>Payment Information:</h4>
            <p><b>Reference ID:</b> <?php echo htmlspecialchars($_SESSION['order_id'] ?? 'N/A'); ?></p>
            <p><b>Transaction ID:</b> <?php echo htmlspecialchars($_SESSION['transaction_id'] ?? 'N/A'); ?></p>
            <p><b>Paid Amount:</b> $<?php echo htmlspecialchars((string)($_SESSION['amount'] ?? 'N/A')); ?> USD</p>
            <p><b>Payment Status:</b> <?php echo htmlspecialchars($_SESSION['payment_status'] ?? 'N/A'); ?></p>
            <?php
            // Clear session variables after use
            unset($_SESSION['order_id'], $_SESSION['transaction_id'], $_SESSION['amount'], $_SESSION['payment_status']);
            ?>
        </div>
        <a href="../src/views/home.php" class="btn-continue">Back to Home</a>
    </div>
</body>

</html>
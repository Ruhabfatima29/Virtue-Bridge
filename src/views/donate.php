
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate</title>
</head>
<body>
    <h1>Donate to Virtue Bridge</h1>
    
    <?php if (isset($_GET['status'])): ?>
        <div class="status">
            <?php if ($_GET['status'] == 'success'): ?>
                <p>Payment successful. Thank you for your donation!</p>
            <?php elseif ($_GET['status'] == 'failure'): ?>
                <p>Payment failed. Please try again.</p>
            <?php elseif ($_GET['status'] == 'invalid'): ?>
                <p>Invalid form submission. Please fill out all required fields.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <form action="process_donation.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" required><br><br>
        
        <button type="submit">Donate</button>
    </form>
</body>
</html>
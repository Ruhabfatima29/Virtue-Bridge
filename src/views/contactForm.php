<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Virtue Bridge</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Contact Form Styles */
        .contact-form {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .contact-form .form-group {
            margin-bottom: 15px;
        }

        .contact-form label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #444;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .contact-form textarea {
            resize: vertical;
        }

        .contact-form button {
            background-color: rgb(0, 109, 119);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .contact-form button:hover {
            background-color: #007b7e;
        }
    </style>
</head>

<body>
    <section id="contact" class="section contact">
        <h2 style="text-align:center;">Contact Us</h2>
        <p style="text-align:center;">Reach out to us with any questions or opportunities to collaborate.</p>
        <form action="" method="POST" class="contact-form">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" name="send">Send Message</button>
        </form>
    </section>

    <?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // Load PHPMailer
    require_once __DIR__ . '/../phpMailer/Exception.php';
    require_once __DIR__ . '/../phpMailer/PHPMailer.php';
    require_once __DIR__ . '/../phpMailer/SMTP.php';    

    if (isset($_POST['send'])) {
        // Sanitize Inputs
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');

        // Validate Inputs
        if (empty($name) || empty($email) || empty($message)) {
            echo "<script>alert('All fields are required.');</script>";
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email format.');</script>";
            exit;
        }

        try {
            // Create PHPMailer instance
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'malacious0101@gmail.com'; // Replace with your email
            $mail->Password = 'ogkp lgyp cwra tecq'; // Replace with your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Sender and Recipient Details
            $mail->setFrom($email, $name); // Sender is the user's email and name
            $mail->addReplyTo($email, $name); // Adds user's email for replies
            $mail->addAddress('malacious0101@gmail.com', 'Virtue Bridge Admin'); // Your email as the recipient

            // Email Content
            $mail->isHTML(true);
            $mail->Subject = 'Virtue Bridge Contact Form Submission';
            $mail->Body = "
                <h3>New Contact Form Submission</h3>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Message:</strong></p>
                <p>$message</p>
            ";

            if ($mail->send()) {
                echo "<script>alert('Your message has been sent successfully!');</script>";
            } else {
                echo "<script>alert('There was an error sending your message. Please try again later.');</script>";
            }
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
            echo "<script>alert('There was an error sending your message. Please try again later.');</script>";
        }
    }
    ?>
</body>

</html>
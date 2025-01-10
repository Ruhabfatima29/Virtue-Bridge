<?php $campaign_id = isset($_GET['campaign_id']) ? htmlspecialchars($_GET['campaign_id']) : null;
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate</title>
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
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

        /* Back Button */
        .back-btn {
            display: inline-block;
            margin: 20px 0;
            padding: 12px 24px;
            font-size: 16px;
            background-color: white;
            color: #006d77;
            text-decoration: none;
            border-radius: 6px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .back-btn:hover {
            background-color: rgb(5, 166, 53);
            color: #ddd;
            box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.2);
        }

        /* Donation Container */
        .donation-container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .donation-form__fieldset {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 6px;
            background: #f9f9f9;
        }

        .donation-form__legend {
            font-size: 1.2rem;
            font-weight: bold;
            color: #006d77;
            margin-bottom: 10px;
        }

        .donation-form__label {
            display: block;
            font-size: 1rem;
            margin: 10px 0 5px;
            color: #333;
        }

        .donation-form__input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            background: #fff;
            margin-bottom: 10px;
        }

        .donation-form__input:focus {
            outline: none;
            border-color: #006d77;
            box-shadow: 0 0 4px rgba(0, 109, 119, 0.2);
        }

        /* Card Element */
        #card-element {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
        }

        /* Donate Button */
        .donation-form__button {
            display: inline-block;
            width: 100%;
            padding: 12px 20px;
            background-color: #006d77;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .donation-form__button:hover {
            background-color: #00878a;
            box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .donation-form__legend {
                font-size: 1rem;
            }

            .donation-header__title {
                font-size: 2rem;
            }

            .donation-header__subtitle {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <header class="donation-header">
        <h1 class="donation-header__title">Donate to Virtue Bridge</h1>
        <p class="donation-header__subtitle">Your support makes a difference. Thank you for your generosity!</p>
    </header>

    <!-- Back Button -->
    <a href="../src/views/allCampaigns.php" class="back-btn">← Back to Campaigns</a>

    <main class="donation-container">
        <form id="payment-form" class="donation-form" action="stripe_payment.php" method="post">
            <!-- Hidden field to pass campaign_id -->
            <input type="hidden" name="campaign_id" value="<?php echo htmlspecialchars($campaign_id); ?>">

            <!-- Personal Information -->
            <fieldset class="donation-form__fieldset">
                <legend class="donation-form__legend">Personal Information</legend>
                <label for="name" class="donation-form__label">Name:</label>
                <input type="text" id="name" name="name" class="donation-form__input" required>

                <label for="email" class="donation-form__label">Email:</label>
                <input type="email" id="email" name="email" class="donation-form__input" required>

                <label for="phone" class="donation-form__label">Phone Number:</label>
                <input type="tel" id="phone" name="phone" class="donation-form__input" required>

                <label for="address" class="donation-form__label">Address:</label>
                <textarea id="address" name="address" class="donation-form__input" required></textarea>

                <label for="amount" class="donation-form__label">Donation Amount:</label>
                <input type="number" id="amount" name="amount" class="donation-form__input" placeholder="Enter amount in USD" min="5" required>
            </fieldset>

            <!-- Card Details -->
            <fieldset class="donation-form__fieldset">
                <legend class="donation-form__legend">Card Details</legend>
                <div id="card-element" class="donation-form__input">
                    <!-- Stripe Elements will insert the card input here -->
                </div>
                <div id="card-errors" class="donation-form__status" role="alert"></div>
            </fieldset>

            <!-- Submit Button -->
            <button type="submit" id="payBtn" class="donation-form__button">Donate</button>
        </form>
    </main>


    <script>
        $(document).ready(function() {
            // Initialize Stripe with your publishable key
            const stripe = Stripe('pk_test_51QVUmnDf49KbCVBOdanlRcxR178ff2624eKRxWFQIcQw6F3PorhEoILVwNP8REwI7LdndiQvKBoDwcHuW0CRHUeZ00KrL6I8fJ'); // Replace with your actual key
            const elements = stripe.elements();

            // Set up Stripe Elements styling
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#32325d',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#fa755a'
                    }
                }
            });

            // Mount the card element
            cardElement.mount('#card-element');

            // Handle form submission
            $('#payment-form').on('submit', async function(event) {
                event.preventDefault(); // Prevent default form submission
                $('#payBtn').attr('disabled', true); // Disable button to prevent multiple submissions
                $('#card-errors').text(''); // Clear any previous error messages

                // Generate Stripe token
                const {
                    token,
                    error
                } = await stripe.createToken(cardElement);

                if (error) {
                    // Show error message in the form
                    $('#card-errors').text(error.message);
                    $('#payBtn').attr('disabled', false); // Re-enable the button
                } else if (token) {
                    // Append the token to the form
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'stripeToken',
                        value: token.id
                    }).appendTo('#payment-form');

                    // Submit the form
                    $('#payment-form').get(0).submit();
                }
            });
        });
    </script>
</body>

</html>
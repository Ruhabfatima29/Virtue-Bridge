<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../assets/css/donate.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <header class="donation-header">
        <h1 class="donation-header__title">Donate to Virtue Bridge</h1>
        <p class="donation-header__subtitle">Your support makes a difference. Thank you for your generosity!</p>
    </header>

    <main class="donation-container">
        <form id="payment-form" class="donation-form" method="Post">
            <!-- Personal Information -->
            <fieldset class="donation-form__fieldset">
                <legend class="donation-form__legend">Personal Information</legend>
                <label for="name" class="donation-form__label">Name:</label>
                <input type="text" id="name" name="name" class="donation-form__input" required>

                <label for="email" class="donation-form__label">Email:</label>
                <input type="email" id="email" name="email" class="donation-form__input" required>

                <label for="amount" class="donation-form__label">Donation Amount:</label>
                <input type="number" id="amount" name="amount" class="donation-form__input" placeholder="Enter amount in USD" min="5" required>
            </fieldset>

            <!-- Card Details -->
            <fieldset class="donation-form__fieldset">
                <legend class="donation-form__legend">Card Details</legend>
                <div id="card-element" class="donation-form__input">
                    <!-- Stripe Elements will be inserted here -->
                </div>
                <div id="card-errors" class="donation-form__status" role="alert"></div>
            </fieldset>

            <!-- Submit Button -->
            <button type="submit" id="payBtn" class="donation-form__button">Donate</button>
        </form>
    </main>

    <script>
        // Initialize Stripe
        const stripe = Stripe('pk_test_51QVUmnDf49KbCVBOdanlRcxR178ff2624eKRxWFQIcQw6F3PorhEoILVwNP8REwI7LdndiQvKBoDwcHuW0CRHUeZ00KrL6I8fJ');
        const elements = stripe.elements();

        // Set up Stripe Elements
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

        // Mount the card input
        cardElement.mount('#card-element');

        // Handle form submission
        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            console.log("Form submitted!"); // Debugging point

            const {
                token,
                error
            } = await stripe.createToken(cardElement);
            console.log("Stripe token:", token, "Error:", error); // Debugging point

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
            } else {
                console.log("Sending token to server..."); // Debugging point
                const formData = new FormData(form);
                formData.append('stripeToken', token.id);

                const response = await fetch('stripe_payment.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                console.log("Server response:", result); // Debugging point
            }
        });
    </script>
</body>

</html>
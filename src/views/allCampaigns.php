<?php
include('../controllers/campaignController.php');
$campaigns = fetchAllCampaigns();
?>

<head>
    <title>Virtue Bridge - All Campaigns</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/home.css">
    <link rel="stylesheet" href="../../assets/css/navbar.css">
    <style>
        /* All Campaigns Page Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .campaign-header {
            color: rgb(0, 109, 119);
            text-align: center;
            margin-top: 50px;
            font-size: 2.5em;
            font-weight: 600;
        }

        /* Campaign Grid Layout - One Card per Line */
        .campaign-cards-container {
            display: grid;
            grid-template-columns: 1fr; /* One card per line */
            gap: 30px;
            padding: 20px;
            margin: 0 10%;
        }

        /* Individual Campaign Card Styling */
        .campaign-card {
            display: flex; /* Using flexbox */
            flex-direction: row; /* Align image on the right and content on the left */
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            justify-content: space-between; /* Create space between image and content */
        }

        .campaign-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .campaign-image {
            width: 40%; /* Adjust image size */
            flex-shrink: 0; /* Prevent image from shrinking */
        }

        .campaign-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .campaign-content {
            width: 55%; /* Content takes 55% of card width */
        }

        .campaign-card h3 {
            font-size: 1.6em;
            color: rgb(0, 109, 119);
            margin-bottom: 10px;
        }

        .campaign-card p {
            font-size: 1em;
            color: #555;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .campaign-card .goal-info {
            font-weight: bold;
            color: #333;
        }

        .donate-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #008C7E;
            color: white;
            font-size: 1.2em;
            border: none;
            border-radius: 5px;
            text-align: center;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .donate-btn:hover {
            background-color: #005d47;
        }

        /* Progress Bar Styling */
        .progress-container {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .progress-bar {
            height: 20px;
            width: 0; /* Default width, will be dynamically updated */
            background-color: #008C7E;
            border-radius: 5px;
            transition: width 0.5s ease;
        }

        .progress-text {
            text-align: center;
            font-weight: bold;
            color: #fff;
            line-height: 20px;
        }
    </style>
</head>

<body>
    <?php include('../includes/navbar.php'); ?>
    <h2 class="campaign-header">All Campaigns</h2>
    <div class="campaign-cards-container">
        <?php foreach ($campaigns as $campaign):
            // Dynamically determine the image path based on the campaign type or ID
            $imagePath = "../../assets/images/campaigns/" . htmlspecialchars($campaign['category_type']) . ".jpg";
            
            // Calculate the progress percentage
            $progress = ($campaign['current_amount'] / $campaign['goal_amount']) * 100;
            $progress = min($progress, 100); // Ensure the progress does not exceed 100%
        ?>
            <div class="campaign-card">
                <div class="campaign-image">
                    <img src="<?php echo $imagePath; ?>" alt="Campaign Image" class="campaign-img">
                </div>
                <div class="campaign-content">
                    <h3><?php echo htmlspecialchars($campaign['title']); ?></h3>
                    <p><?php echo htmlspecialchars($campaign['description']); ?></p>
                    <p class="goal-info">Goal: $<?php echo htmlspecialchars($campaign['goal_amount']); ?></p>
                    <p class="goal-info">Raised: $<?php echo htmlspecialchars($campaign['current_amount']); ?></p>

                    <!-- Progress Bar -->
                    <div class="progress-container">
                        <div class="progress-bar" style="width: <?php echo $progress; ?>%;">
                            <div class="progress-text"><?php echo round($progress); ?>% Funded</div>
                        </div>
                    </div>

                    <!-- Donate Now Form -->
                    <form action="..\..\stripe\donate.php" method="POST">
                        <input type="hidden" name="campaign_id" value="<?php echo htmlspecialchars($campaign['campaign_id']); ?>">
                        <button type="submit" class="donate-btn">Donate Now</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

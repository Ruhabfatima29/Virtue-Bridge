<?php include('../includes/adminNavBar.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Campaigns</title>
    <link rel="stylesheet" href="../../assets/css/creatorDashboard.css">
    <<link rel="stylesheet" href="../assets/css/manageCampaigns.css">
</head>

<body>

    <div class="container">
        <h1>Manage Campaigns</h1>

        <!-- Display Messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="success-message"><?php echo $_SESSION['message'];
                                            unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message"><?php echo $_SESSION['error'];
                                        unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Filter Section -->
        <div class="filter-container">
            <select id="campaignFilter" class="filter-select">
                <option value="all" <?php echo isset($_GET['status']) && $_GET['status'] == 'all' ? 'selected' : ''; ?>>All Campaigns</option>
                <option value="active" <?php echo isset($_GET['status']) && $_GET['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="paused" <?php echo isset($_GET['status']) && $_GET['status'] == 'paused' ? 'selected' : ''; ?>>Paused</option>
                <option value="completed" <?php echo isset($_GET['status']) && $_GET['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
            </select>
        </div>

        <!-- Campaigns Section -->
        <div class="card-container" id="campaignCards">
            <?php include '../controllers/manageCampaignController.php'; ?>
            <?php if ($allCampaignsResult->num_rows > 0): ?>
                <?php while ($row = $allCampaignsResult->fetch_assoc()): ?>
                    <div class="card" data-status="<?php echo htmlspecialchars($row['status']); ?>">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p>Creator: <?php echo htmlspecialchars($row['creator_name']); ?></p>
                        <p>Amount Required: $<?php echo htmlspecialchars($row['goal_amount']); ?></p>
                        <p>Amount Collected: $<?php echo htmlspecialchars($row['current_amount']); ?></p>
                        <p class="status <?php echo $row['status'] == 5 ? 'paused' : ($row['status'] == 3 ? 'completed' : ''); ?>">
                            <?php echo $row['status'] == 5 ? 'Paused' : ($row['status'] == 3 ? 'Completed' : 'Active'); ?>
                        </p>
                        <form method="POST">
                            <input type="hidden" name="campaign_id" value="<?php echo $row['campaign_id']; ?>">
                            <input type="hidden" name="action" value="<?php echo $row['status'] == 5 ? 'activate' : 'pause'; ?>">
                            <button type="submit">
                                <?php echo $row['status'] == 5 ? 'Activate' : 'Pause'; ?>
                            </button>
                        </form>

                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <!-- Empty state message with a Unicode icon -->
                <div class="empty-state">
                    <p>No campaigns found based on the current filter.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.getElementById('campaignFilter').addEventListener('change', function() {
            const filter = this.value;
            if (filter === 'all') {
                window.location.href = 'manageCampaigns.php';
            } else {
                window.location.href = `manageCampaigns.php?status=${filter}`;
            }
        });

        // JavaScript for filtering campaigns on the client side
        const filterSelect = document.getElementById('campaignFilter');
        const cards = document.querySelectorAll('.card');

        filterSelect.addEventListener('change', () => {
            const filter = filterSelect.value;
            cards.forEach(card => {
                const status = card.getAttribute('data-status');
                if (filter === 'all' ||
                    (filter === 'active' && status == 4) ||
                    (filter === 'paused' && status == 5) ||
                    (filter === 'completed' && status == 6)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
    <style>
        .container {
            background-color: #f5f5f5;
        }

        .container h1 {
            color: rgb(32, 128, 133);
            text-align: center;
            font-weight: bold;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 80px;
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 2px solidrgb(255, 183, 141);
        }

        .card:hover {
            cursor: pointer;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }

        .card h3 {
            margin: 0;
            font-size: 20px;
            color: #006D77;
        }

        .card p {
            margin: 5px 0;
            color: #555;
        }

        .card .status {
            margin: 10px 0;
            font-weight: bold;
            color: #28a745;
        }

        .card .status.paused {
            color: #f44336;
        }

        .card button {
            background-color: rgb(26, 79, 100);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            align-self: flex-start;
        }

        .card button:hover {
            background-color: #00a896;
        }

        .container {
            margin-left: 250px;
            /* To avoid overlapping with side nav */
            padding: 20px;
        }

        .section-title {
            margin: 20px 0;
            color: #006D77;
            font-size: 24px;
        }


        /* Styling for cards and filters */
        .filter-container {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .filter-select {
            padding: 8px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #007bff;
        }

        .card .status {
            margin: 10px 0;
            font-weight: bold;
            color: #28a745;
        }

        .card .status.paused {
            color: #f44336;
        }

        .card .status.completed {
            color: #6c757d;
        }


        .empty-state {
            text-align: center;
        }
    </style>
</body>

</html>
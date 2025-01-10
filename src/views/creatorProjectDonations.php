<?php
// Include database connection and necessary files
require_once __DIR__ . '/../controllers/creatorProjectsController.php';

// Check if the campaign ID is passed (for viewing donations of a specific campaign)
$campaign_id = isset($_GET['campaign_id']) ? $_GET['campaign_id'] : null;

$projects = getCampaignsByUserId();

// Fetch the donations if a campaign ID is passed
$campaign_id = isset($_GET['campaign_id']) ? $_GET['campaign_id'] : null;
$donations = [];
if ($campaign_id !== null) {
    $donations = getDonationsByCampaignIdForResults($campaign_id);
}
?>

<<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Creator Projects and Donations</title>
        <link rel="stylesheet" href="../../assets/css/creatorDashboard.css">
        <link rel="stylesheet" href="../../assets/css/myProjects.css">
        <style>
            :root {
                --primary-color: #007b7e;
                --secondary-color: #f8f9fa;
                --accent-color: #005658;
                --success-color: #28a745;
                --warning-color: #ffc107;
                --danger-color: #dc3545;
            }

            body {
                background-color: #f5f8fa;
                font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            }

            .main-content {
                margin-left: 250px;
                padding: 2rem;
                background-color: transparent;
            }

            .content-container {
                width: calc(100% - 2rem);
                max-width: 1400px;
                margin: 0 auto;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }

            .stat-card {
                background: white;
                padding: 1.5rem;
                border-radius: 12px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                transition: transform 0.2s ease;
            }

            .stat-card:hover {
                transform: translateY(-2px);
            }

            .campaign-header,
            .donation-header {
                color: var(--primary-color);
                font-weight: 600;
                position: relative;
                padding-bottom: 0.5rem;
                margin-bottom: 1.5rem;
            }

            .campaign-header::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 60px;
                height: 4px;
                background: var(--primary-color);
                border-radius: 2px;
            }

            .campaign-table,
            .donation-table {
                width: 100%;
                background: white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                margin-bottom: 2rem;
            }

            .campaign-table th,
            .donation-table th {
                background-color: var(--primary-color);
                color: white;
                font-weight: 500;
                text-transform: uppercase;
                font-size: 0.85rem;
                padding: 1rem;
            }

            .campaign-table td,
            .donation-table td {
                padding: 1rem;
                border-bottom: 1px solid #edf2f7;
                color: #4a5568;
            }

            .campaign-table tr:last-child td {
                border-bottom: none;
            }

            .campaign-table td a,
            .donation-table td a {
                color: var(--primary-color);
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s ease;
            }

            .campaign-table td a:hover {
                color: var(--accent-color);
            }

            .status-badge {
                padding: 0.5rem 1rem;
                border-radius: 20px;
                font-size: 0.85rem;
                font-weight: 500;
                text-transform: capitalize;
            }

            .status-active {
                background-color: #e3f8e5;
                color: var(--success-color);
            }

            .status-pending {
                background-color: #fff8e6;
                color: var(--warning-color);
            }

            .amount-cell {
                font-family: 'Monaco', monospace;
                font-weight: 500;
            }

            .empty-state {
                text-align: center;
                padding: 3rem;
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            }

            .empty-state p {
                color: #718096;
                margin-top: 1rem;
            }

            @media (max-width: 768px) {
                .main-content {
                    margin-left: 0;
                    padding: 1rem;
                }

                .stats-grid {
                    grid-template-columns: 1fr;
                }

                .campaign-table,
                .donation-table {
                    display: block;
                    overflow-x: auto;
                    white-space: nowrap;
                }
            }
        </style>
    </head>

    <body>
        <?php include('../includes/creatorNavBar.php'); ?>
        <div class="main-content">
            <div class="content-container">
                <h2 class="campaign-header">Your Campaigns</h2>

                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Campaigns</h3>
                        <div class="stat-value"><?php echo count($projects); ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Raised</h3>
                        <div class="stat-value">$<?php
                                                    $total = array_sum(array_column($projects, 'current_amount'));
                                                    echo number_format($total, 2);
                                                    ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Goal Progress</h3>
                        <div class="stat-value"><?php
                                                $totalGoals = array_sum(array_column($projects, 'goal_amount'));
                                                $progress = ($totalGoals > 0) ?
                                                    round(($total / $totalGoals) * 100) : 0;
                                                echo $progress . '%';
                                                ?></div>
                    </div>
                </div>

                <?php if (!empty($projects)) : ?>
                    <table class="campaign-table">
                        <thead>
                            <tr>
                                <th>Campaign Title</th>
                                <th>Status</th>
                                <th>Goal Amount</th>
                                <th>Current Amount</th>
                                <th>Progress</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $campaign) : ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($campaign['title']); ?></strong>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($campaign['status']); ?>">
                                            <?php echo htmlspecialchars($campaign['status']); ?>
                                        </span>
                                    </td>
                                    <td class="amount-cell">$<?php echo number_format($campaign['goal_amount'], 2); ?></td>
                                    <td class="amount-cell">$<?php echo number_format($campaign['current_amount'], 2); ?></td>
                                    <td>
                                        <?php
                                        $campaignProgress = ($campaign['goal_amount'] > 0) ?
                                            round(($campaign['current_amount'] / $campaign['goal_amount']) * 100) : 0;
                                        ?>
                                        <div style="background: #edf2f7; border-radius: 10px; height: 8px; width: 100%;">
                                            <div style="background: var(--primary-color); border-radius: 10px; height: 8px; width: <?php echo $campaignProgress; ?>%;"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="creatorProjectDonations.php?campaign_id=<?php echo $campaign['campaign_id']; ?>">
                                            View Donations
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <div class="empty-state">
                        <h3>No Campaigns Yet</h3>
                        <p>Start your first campaign to begin collecting donations!</p>
                    </div>
                <?php endif; ?>

                <?php if ($campaign_id) : ?>
                    <?php
                    $donations = getDonationsByCampaignId($campaign_id);
                    $selected_campaign = getCampaignById($campaign_id);
                    ?>

                    <h3 class="donation-header">
                        Donations for "<?php echo htmlspecialchars($selected_campaign['title']); ?>"
                    </h3>

                    <?php
                    if (!empty($donations)) : ?>
                        <table class="donation-table">
                            <thead>
                                <tr>
                                    <!-- <th>Donor Email</th> -->
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($donations as $donation) : ?>
                                    <tr>
                                        <!-- 
                                        <td>
                                            <strong><?php echo htmlspecialchars($donation['donor_email']); ?></strong> 
                                        </td>
                                        -->
                                        <td class="amount-cell">
                                            $<?php echo number_format($donation['amount'], 2); ?>
                                        </td>
                                        <td>
                                            <?php echo date('F j, Y', strtotime($donation['donation_date'])); ?>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <div class="empty-state">
                            <h3>No Donations Yet</h3>
                            <p>Share your campaign to start receiving donations!</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </body>

    </html>
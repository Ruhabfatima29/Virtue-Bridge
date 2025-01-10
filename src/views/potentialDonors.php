<?php require_once __DIR__ . '/../../config/db.php'; ?>
<?php include('../includes/adminNavBar.php'); ?>
<?php
// Define the path to the CSV file
define('CSV_FILE_PATH', ROOT_PATH . '/assets/uploads/data.csv');

// Check if the file exists
if (!file_exists(CSV_FILE_PATH)) {
    die("Error: The donor data file is missing.");
}

// Read and process the CSV data
$data = file_get_contents(CSV_FILE_PATH);

// Process lines and ignore the first header row
$lines = explode("\n", trim($data));
array_shift($lines); // Remove the header row

// Function to clean amount string
function cleanAmount($amount) {
    return str_replace(['$', ','], '', $amount);
}

// Process donors into a structured array
$processed_donors = [];
foreach ($lines as $line) {
    $line = trim($line);
    if (!empty($line) && 
        strpos($line, "Join this list") === false && 
        strpos($line, "See top") === false) {
        
        $parts = explode(',', $line); // Assuming CSV data is comma-separated
        if (count($parts) >= 3) { // Ensure we have all necessary parts
            $processed_donors[] = [
                'name' => trim($parts[0]),
                'amount' => trim($parts[1]),
                'time' => trim($parts[2])
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/creatorDashboard.css">
    <title>Donor Recognition</title>
    <style>
        
        :root {
            --donor-primary-color: #004346;
            --donor-secondary-color: #006D77;
            --donor-text-color: #333;
            --donor-border-color: #e0e0e0;
            --donor-hover-color: #f5f5f5;
            --donor-success-color: #2e7d32;
        }

        .donor-page-reset {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .donor-page-body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            color: var(--donor-text-color);
        }

        .donor-page-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .donor-main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
            overflow-x: hidden;
        }

        .donor-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .donor-header-section {
            text-align: center;
            padding-bottom: 2rem;
            border-bottom: 2px solid var(--donor-border-color);
            margin-bottom: 2rem;
        }

        .donor-header-title {
            color: var(--donor-primary-color);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .donor-header-subtitle {
            color: #666;
        }

        .donor-stats-section {
            background: var(--donor-primary-color);
            color: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .donor-stats-box {
            text-align: center;
        }

        .donor-stats-number {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .donor-stats-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .donor-list-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .donor-table-header {
            background-color: #f8f9fa;
            color: var(--donor-primary-color);
            font-weight: 600;
            text-align: left;
            padding: 1rem;
            border-bottom: 2px solid var(--donor-border-color);
        }

        .donor-table-cell {
            padding: 1rem;
            border-bottom: 1px solid var(--donor-border-color);
        }

        .donor-table-row:hover {
            background-color: var(--donor-hover-color);
        }

        .donor-amount-cell {
            color: var(--donor-success-color);
            font-weight: 600;
        }

        .donor-cta-section {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid var(--donor-border-color);
        }

        .donor-cta-button {
            display: inline-block;
            padding: 1rem 2rem;
            background-color: var(--donor-secondary-color);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .donor-cta-button:hover {
            background-color: var(--donor-primary-color);
        }

        @media (max-width: 768px) {
            .donor-main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .donor-stats-section {
                flex-direction: column;
                gap: 1rem;
            }

            .donor-container {
                padding: 1rem;
            }

            .donor-list-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body class="donor-page-body">
    <div class="donor-page-wrapper">
        <div class="donor-main-content">
            <div class="donor-container">
                <div class="donor-header-section">
                    <h1 class="donor-header-title">Donor Recognition</h1>
                    <p class="donor-header-subtitle">Celebrating our community of supporters</p>
                </div>

                <div class="donor-stats-section">
                    <?php
                    $total_amount = 0;
                    foreach ($processed_donors as $donor) {
                        $total_amount += (float)cleanAmount($donor['amount']);
                    }
                    ?>
                    <div class="donor-stats-box">
                        <h2 class="donor-stats-number">$<?php echo number_format($total_amount, 2); ?></h2>
                        <p class="donor-stats-label">Total Contributions</p>
                    </div>
                    <div class="donor-stats-box">
                        <h2 class="donor-stats-number"><?php echo count($processed_donors); ?></h2>
                        <p class="donor-stats-label">Generous Donors</p>
                    </div>
                </div>

                <table class="donor-list-table">
                    <thead>
                        <tr>
                            <th class="donor-table-header">Donor Name</th>
                            <th class="donor-table-header">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($processed_donors as $donor): ?>
                            <tr class="donor-table-row">
                                <td class="donor-table-cell">
                                    <?php echo htmlspecialchars(!empty($donor['name']) && $donor['name'] !== 'Anonymous' ? $donor['name'] : 'Anonymous Donor'); ?>
                                </td>
                                <td class="donor-table-cell donor-amount-cell">
                                    <?php echo htmlspecialchars($donor['amount']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="donor-cta-section">
                    <a href="https://www.gofundme.com/f/help-me-cover-up-front-grad-school-moving-costs" target="_blank" class="donor-cta-button">
                        Join Our Donor Community
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
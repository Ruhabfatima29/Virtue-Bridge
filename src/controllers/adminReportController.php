<?php
// getAdminStats.php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

function getAdminStats($conn)
{
    // Get total donations
    $donationQuery = "SELECT COALESCE(SUM(amount), 0) as total FROM donations";
    $donationResult = $conn->query($donationQuery);
    $totalDonations = $donationResult->fetch_assoc()['total'];

    // Get active campaigns count
    $campaignQuery = "SELECT COUNT(*) as count FROM campaigns WHERE status = 4"; // Assuming 4 is active status
    $campaignResult = $conn->query($campaignQuery);
    $activeCampaigns = $campaignResult->fetch_assoc()['count'];

    // Get total donors
    $donorQuery = "SELECT COUNT(DISTINCT donor_table_id) as count FROM donations";
    $donorResult = $conn->query($donorQuery);
    $totalDonors = $donorResult->fetch_assoc()['count'];

    // Calculate success rate
    $successQuery = "SELECT 
        (COUNT(CASE WHEN current_amount >= goal_amount THEN 1 END) * 100.0 / COUNT(*)) as rate 
        FROM campaigns WHERE status != 5"; // Excluding pending campaigns
    $successResult = $conn->query($successQuery);
    $successRate = round($successResult->fetch_assoc()['rate'], 1);

    return [
        'totalDonations' => $totalDonations,
        'activeCampaigns' => $activeCampaigns,
        'totalDonors' => $totalDonors,
        'successRate' => $successRate
    ];
}

function getRecentTransactions($conn, $limit = 10)
{
    try {
        $query = "SELECT d.donation_id as id, c.title as campaign, d.amount, d.donation_date as date FROM donations d JOIN campaigns c ON d.campaign_id = c.campaign_id ORDER BY d.donation_date DESC LIMIT ?";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param('i', $limit);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();

        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }

        return $transactions;
    } catch (Exception $e) {
        error_log("Transaction query error: " . $e->getMessage());
        throw $e;
    }
}

function getDonationTrends($conn, $period = 'week')
{
    $query = "";
    switch ($period) {
        case 'week':
            $query = "SELECT DATE(dates.donation_date) AS date, COALESCE(SUM(donations.amount), 0) AS total FROM ( SELECT CURDATE() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS donation_date FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6) AS a CROSS JOIN (SELECT 0 AS a) AS b CROSS JOIN (SELECT 0 AS a) AS c ) AS dates LEFT JOIN donations ON DATE(donations.donation_date) = DATE(dates.donation_date) WHERE dates.donation_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY dates.donation_date ORDER BY dates.donation_date";
            break;

        case 'month':
            $query = "SELECT DATE(dates.donation_date) AS date, COALESCE(SUM(donations.amount), 0) AS total FROM ( SELECT CURDATE() - INTERVAL (a.a + (10 * b.a)) DAY AS donation_date FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3) AS b ) AS dates LEFT JOIN donations ON DATE(donations.donation_date) = dates.donation_date WHERE dates.donation_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY dates.donation_date ORDER BY dates.donation_date";
            break;

        case 'year':
            $query = "SELECT 
            dates.month_start AS date,
            COALESCE(SUM(donations.amount), 0) AS total
        FROM (
            SELECT 
                DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL n MONTH), '%Y-%m-01') AS month_start
            FROM (
                SELECT @row := @row + 1 AS n
                FROM (SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL 
                    SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL 
                    SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11) t,
                (SELECT @row := -1) r
            ) numbers
        ) dates
        LEFT JOIN donations ON DATE_FORMAT(donations.donation_date, '%Y-%m') = DATE_FORMAT(dates.month_start, '%Y-%m')
        GROUP BY dates.month_start
        ORDER BY dates.month_start;
        ";
                    break;
    }

    $result = $conn->query($query);
    if (!$result) {
        error_log("Query error: " . $conn->error);
        throw new Exception("Database query failed");
    }

    $trends = [];
    while ($row = $result->fetch_assoc()) {
        $trends[] = $row;
    }
    return $trends;
}

try {
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'stats':
            $response = getAdminStats($conn);
            break;
        case 'transactions':
            $response = getRecentTransactions($conn);
            break;
        case 'trends':
            $period = $_GET['period'] ?? 'week';
            $response = getDonationTrends($conn, $period);
            break;
        default:
            http_response_code(400);
            $response = ['error' => 'Invalid action specified'];
    }

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error occurred',
        'details' => $e->getMessage()
    ]);
}

<?php include('../includes/adminNavBar.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/creatorDashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #4a90e2;
            --success-color: #2ecc71;
            --warning-color: #f1c40f;
            --danger-color: #e74c3c;
            --dark-color: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f6fa;
        }

        .dashboard-container {
            padding: 2rem;
            margin-left: 250px;
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
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            margin: 0;
            color: var(--dark-color);
            font-size: 1.1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin: 0.5rem 0;
        }

        .stat-change {
            font-size: 0.9rem;
            color: var(--success-color);
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-container {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .chart-title {
            margin: 0;
            color: var(--dark-color);
            font-size: 1.2rem;
        }

        .chart-filters {
            display: flex;
            gap: 1rem;
        }

        select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .table-container {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            color: var(--dark-color);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-success {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <?php include('../includes/adminNavBar.php'); ?>

    <div class="dashboard-container">
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Donations</h3>
                <div class="stat-value">$<span id="totalDonations">0</span></div>
                <div class="stat-change">+5.2% from last month</div>
            </div>
            <div class="stat-card">
                <h3>Active Campaigns</h3>
                <div class="stat-value" id="activeCampaigns">0</div>
                <div class="stat-change">+3 new this week</div>
            </div>
            <div class="stat-card">
                <h3>Total Donors</h3>
                <div class="stat-value" id="totalDonors">0</div>
                <div class="stat-change">+12% from last month</div>
            </div>
            <div class="stat-card">
                <h3>Success Rate</h3>
                <div class="stat-value" id="successRate">0%</div>
                <div class="stat-change">+2.5% improvement</div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="charts-grid">
            <!-- Donation Trends Chart -->
            <div class="chart-container">
                <div class="chart-header">
                    <h2 class="chart-title">Donation Trends</h2>
                    <div class="chart-filters">
                        <select id="donationTrendPeriod">
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                        </select>
                    </div>
                </div>
                <canvas id="donationTrendsChart"></canvas>
            </div>

            <!-- Campaign Success Rate Chart -->
            <div class="chart-container">
                <div class="chart-header">
                    <h2 class="chart-title">Campaign Success Rates</h2>
                    <div class="chart-filters">
                        <select id="campaignTypeFilter">
                            <option value="all">All Types</option>
                            <option value="education">Education</option>
                            <option value="health">Health</option>
                            <option value="community">Community</option>
                        </select>
                    </div>
                </div>
                <canvas id="campaignSuccessChart"></canvas>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="table-container">
            <div class="chart-header">
                <h2 class="chart-title">Recent Transactions</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Campaign</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="recentTransactionsBody">
                    <!-- Transactions will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Initialize charts and load data
        document.addEventListener('DOMContentLoaded', function() {
            initializeDonationTrendsChart();
            initializeCampaignSuccessChart();
            loadDashboardStats();
            loadRecentTransactions();
        });

        let donationTrendsChart; // Declare this globally

function initializeDonationTrendsChart() {
    const ctx = document.getElementById('donationTrendsChart').getContext('2d');
    donationTrendsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Donations ($)',
                data: [],
                borderColor: '#4a90e2',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(74, 144, 226, 0.1)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '$' + value.toLocaleString()
                    }
                }
            }
        }
    });

    // Add event listener for period change
    document.getElementById('donationTrendPeriod').addEventListener('change', function(e) {
        loadDonationTrends(e.target.value);
    });

    // Load initial data
    loadDonationTrends('week');
}

function loadDonationTrends(period) {
    fetch(`../controllers/adminReportController.php?action=trends&period=${period}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            console.log('Received trend data:', data); // Debug log
            
            const labels = data.map(item => {
                const date = new Date(item.date);
                switch(period) {
                    case 'week':
                        return date.toLocaleDateString('default', { weekday: 'short', day: 'numeric' });
                    case 'month':
                        return date.toLocaleDateString('default', { month: 'short', day: 'numeric' });
                    case 'year':
                        return date.toLocaleDateString('default', { year: 'numeric', month: 'short' });
                    default:
                        return date.toLocaleDateString();
                }
            });
            
            const values = data.map(item => parseFloat(item.total));

            donationTrendsChart.data.labels = labels;
            donationTrendsChart.data.datasets[0].data = values;
            donationTrendsChart.update();
        })
        .catch(error => {
            console.error('Error loading donation trends:', error);
            alert('Failed to load donation trends data');
        });
}
        function initializeCampaignSuccessChart() {
            const ctx = document.getElementById('campaignSuccessChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Education', 'Health', 'Community', 'Others'],
                    datasets: [{
                        label: 'Success Rate (%)',
                        data: [75, 68, 82, 71],
                        backgroundColor: [
                            '#4a90e2',
                            '#2ecc71',
                            '#f1c40f',
                            '#e74c3c'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: value => value + '%'
                            }
                        }
                    }
                }
            });
        }

        function loadDashboardStats() {
            fetch('../controllers/adminReportController.php?action=stats')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Received stats:', data); // Debug log
                    document.getElementById('totalDonations').textContent = data.totalDonations.toLocaleString();
                    document.getElementById('activeCampaigns').textContent = data.activeCampaigns;
                    document.getElementById('totalDonors').textContent = data.totalDonors.toLocaleString();
                    document.getElementById('successRate').textContent = data.successRate + '%';
                })
                .catch(error => {
                    console.error('Error loading stats:', error);
                    alert('Failed to load dashboard statistics: ' + error.message);
                });
        }

        function loadRecentTransactions() {
            fetch('../controllers/adminReportController.php?action=transactions')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Received transactions:', data); // Debug log
                    const tbody = document.getElementById('recentTransactionsBody');
                    tbody.innerHTML = data.map(transaction => `
                <tr>
                    <td>${transaction.id}</td>
                    <td>${transaction.campaign}</td>
                    <td>$${transaction.amount.toLocaleString()}</td>
                    <td>${new Date(transaction.date).toLocaleDateString()}</td>
                </tr>
            `).join('');
                })
                .catch(error => {
                    console.error('Error loading transactions:', error);
                    alert('Failed to load transactions: ' + error.message);
                });
        }
    </script>
</body>

</html>
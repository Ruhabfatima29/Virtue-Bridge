<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Campaign</title>
    <link rel="stylesheet" href="../../assets/css/creatorDashboard.css">
    <link rel="stylesheet" href="../../assets/css/addCampaign.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e1e8ef;
        }

        .page-header h1 {
            font-size: 2rem;
            color: #007b7e;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .breadcrumb {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0;
        }

        /* Improve spacing for better visual hierarchy */
        .content-area {
            padding: 2rem 2.5rem;
        }

        .stats-cards {
            margin-top: 1rem;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .content-area {
            flex: 1;
            padding: 2rem;
            margin-left: 70px;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card h3 {
            color: #333;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            font-size: 1.5rem;
            color: #007bff;
            margin: 0;
        }

        #literacy-rate-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        #literacy-rate-section h2 {
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        #literacy-rate-section p {
            color: #666;
            margin-bottom: 2rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin: 0 auto;
        }

        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .form-container h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e1e8ef;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #007bff;
            outline: none;
        }

        .submit-btn {
            background: rgb(2, 77, 78);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background: rgb(9, 110, 112);
        }

        canvas {
            max-width: 100%;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <?php include('../includes/creatorNavBar.php'); ?>

    <div class="dashboard-container">
        <div class="content-area">
            <!-- Add new header section -->
            <div class="page-header">
                <h1>Campaign Dashboard</h1>
                <p class="breadcrumb">Login / Home / Add New Campaign</p>
            </div>
            <!-- Stats Cards -->
            <div class="stats-cards">
                <div class="stat-card">
                    <h3>Total Communities</h3>
                    <p id="totalCommunities">0</p>
                </div>
                <div class="stat-card">
                    <h3>Average Literacy Rate</h3>
                    <p id="avgLiteracyRate">0%</p>
                </div>
            </div>

            <!-- Literacy Rate Chart Section -->
            <div id="literacy-rate-section">
                <h2>Community Literacy Rates</h2>
                <p>Target areas with low literacy rates to make the most impact</p>
                <div class="chart-container">
                    <canvas id="literacyRateChart"></canvas>
                </div>
            </div>

            <!-- Campaign Form -->
            <div class="form-container">
                <h2>Create New Campaign</h2>
                <form method="POST" action="../controllers/AddCampaignController.php">
                    <div class="form-group">
                        <label for="campaignName">Campaign Name</label>
                        <input type="text" name="campaignName" id="campaignName" required>
                    </div>

                    <div class="form-group">
                        <label for="goalAmount">Goal Amount ($)</label>
                        <input type="number" name="goalAmount" id="goalAmount" step="1" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="type">Campaign Type</label>
                        <select name="type" id="type" required>
                            <?php foreach ($types as $type): ?>
                                <option value="<?= htmlspecialchars($type['id']) ?>">
                                    <?= htmlspecialchars($type['value']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="community">Local Community</label>
                        <select name="community" id="community" required onchange="updateChart(this.value)">
                            <?php foreach ($communities as $community): ?>
                                <option value="<?= htmlspecialchars($community['community_id']) ?>">
                                    <?= htmlspecialchars($community['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Campaign Description</label>
                        <textarea name="description" id="description" rows="5"></textarea>
                    </div>

                    <button type="submit" class="submit-btn">Create Campaign</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const literacyData = {};
        let chart = null;

        // Fetch literacy data from the server
        fetch('../controllers/getCommunityData.php')
            .then(response => response.json())
            .then(data => {
                // Store the data and update stats
                data.forEach(item => {
                    literacyData[item.community_id] = {
                        name: item.name,
                        rate: parseFloat(item.literacy_rate) || 0,
                        area: item.area,
                        city: item.city
                    };
                });

                // Update statistics
                document.getElementById('totalCommunities').textContent = data.length;
                const avgRate = data.reduce((acc, curr) => acc + (parseFloat(curr.literacy_rate) || 0), 0) / data.length;
                document.getElementById('avgLiteracyRate').textContent = avgRate.toFixed(1) + '%';

                // Initialize chart with all communities
                initializeChart(data);

                // Update chart for selected community
                const firstCommunityId = document.querySelector('#community').value;
                if (firstCommunityId) {
                    updateChart(firstCommunityId);
                }
            })
            .catch(error => console.error('Error fetching literacy data:', error));

        function initializeChart(data) {
            const ctx = document.getElementById('literacyRateChart').getContext('2d');

            // Destroy existing chart if it exists
            if (chart) {
                chart.destroy();
            }

            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.name),
                    datasets: [{
                        label: 'Literacy Rate (%)',
                        data: data.map(item => parseFloat(item.literacy_rate) || 0),
                        backgroundColor: data.map(item =>
                            `rgba(${Math.floor(255 * (1 - item.literacy_rate/100))}, 
                        ${Math.floor(255 * (item.literacy_rate/100))}, 255, 0.7)`
                        ),
                        borderColor: '#0056b3',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const item = data[context.dataIndex];
                                    return [
                                        `Literacy Rate: ${context.formattedValue}%`,
                                        `Area: ${item.area}`,
                                        `City: ${item.city}`
                                    ];
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Literacy Rate (%)'
                            }
                        },
                        x: {
                            ticks: {
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        }

        function updateChart(communityId) {
            const selectedCommunity = literacyData[communityId];
            if (selectedCommunity) {
                const rate = selectedCommunity.rate;
                const color = `rgba(${Math.floor(255 * (1 - rate/100))}, 
                          ${Math.floor(255 * (rate/100))}, 255, 0.7)`;

                chart.data.labels = [selectedCommunity.name];
                chart.data.datasets[0].data = [rate];
                chart.data.datasets[0].backgroundColor = [color];
                chart.update();
            }
        }
    </script>
</body>

</html>
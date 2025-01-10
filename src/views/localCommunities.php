<?php include('../includes/adminNavBar.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/creatorDashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Pakistan Literacy Rates Analysis</title>
    <style>
        .content-wrapper {
            display: flex;
            margin-left: 240px;
            padding: 20px;
        }

        .campaign-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f9fafc;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

        .campaign-container h1 {
            text-align: center;
            color: #007b7e;
            margin-bottom: 20px;
        }

        .chart-container {
            position: relative;
            margin: 20px auto;
            max-width: 800px;
            height: 400px;
            margin-bottom: 40px;
        }

        .insights {
        margin: 20px;
        padding: 20px;
        background: linear-gradient(145deg, #f5f7fa, #d1e1f0);
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;
    }

    .insights:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
    }

    .insights h3 {
        color: #43aa8b;
        font-family: 'Arial', sans-serif;
        font-size: 1.6rem;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: center;
    }

    .insights p {
        font-size: 1rem;
        color: #555;
        margin-bottom: 12px;
        line-height: 1.6;
    }

    .insights strong {
        color: #004aad;
        font-weight: bold;
    }

    .insights ul {
        list-style-type: none;
        padding-left: 0;
    }

    .insights ul li {
        font-size: 1rem;
        color: #333;
        margin: 8px 0;
        display: flex;
        align-items: center;
        position: relative;
    }

    .insights ul li::before {
        content: '✔';
        color: #43aa8b; /* Green checkmark */
        position: absolute;
        left: -20px;
    }

    .insights ul li:nth-child(even) {
        background-color: #f2f9fc;
        border-radius: 5px;
        padding: 8px 10px;
    }
    </style>
</head>

<body>
    <div class="content-wrapper">
        <div class="campaign-container">
            <h1>Pakistan Literacy Rates Dashboard</h1>

            <!-- Chart for Provincial and Urban/Rural Literacy Rates -->
            <div class="chart-container">
                <canvas id="literacyRatesChart"></canvas>
            </div>

            <!-- Chart for District-Level Literacy Rates -->
            <div class="chart-container">
                <canvas id="districtLiteracyChart"></canvas>
            </div>

            <div class="insights">
    <h3>Key Insights</h3>
    <p><strong>National Literacy Rate (Latest Data):</strong> 62.8%</p>
    <p><strong>Provincial Literacy Rates:</strong></p>
    <ul>
        <li>Punjab: 66.3%</li>
        <li>Sindh: 61.8%</li>
        <li>Khyber Pakhtunkhwa (KP): 55.1%</li>
        <li>Balochistan: 54.5%</li>
    </ul>
    <p><strong>Urban vs. Rural Literacy Rates:</strong></p>
    <ul>
        <li>Urban Areas: 76%</li>
        <li>Rural Areas: 51%</li>
    </ul>
</div>
        </div>
    </div>

    <script>
        // Data for literacy rates in Pakistan (Provincial and Urban/Rural)
        const literacyData = {
            labels: ['National', 'Punjab', 'Sindh', 'Khyber Pakhtunkhwa', 'Balochistan', 'Urban', 'Rural'],
            datasets: [{
                label: 'Literacy Rate (%)',
                data: [62.8, 66.3, 61.8, 55.1, 54.5, 76, 51],
                backgroundColor: [
                    '#f94144', // National
                    '#f3722c', // Punjab
                    '#f8961e', // Sindh
                    '#f9844a', // KP
                    '#f9c74f', // Balochistan
                    '#90be6d', // Urban
                    '#43aa8b'  // Rural
                ],
                borderColor: [
                    '#f94144',
                    '#f3722c',
                    '#f8961e',
                    '#f9844a',
                    '#f9c74f',
                    '#90be6d',
                    '#43aa8b'
                ],
                borderWidth: 2
            }]
        };

        // Literacy Rates Chart (Provincial and Urban/Rural)
        const ctx = document.getElementById('literacyRatesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: literacyData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Literacy Rates in Pakistan (2022/23)'
                    },
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Literacy Rate (%)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Region/Category'
                        }
                    }
                }
            }
        });

        // Data for district-level literacy rates
        const districtData = {
            labels: ['Sialkot', 'Vehari', 'Karachi', 'Lahore', 'Peshawar'],
            datasets: [{
                label: 'District Literacy Rate (%)',
                data: [88.37, 69.10, 75.20, 83.50, 78.45], // Example data for districts
                backgroundColor: ['#f94144', '#f3722c', '#f8961e', '#f9844a', '#f9c74f'],
                borderColor: ['#f94144', '#f3722c', '#f8961e', '#f9844a', '#f9c74f'],
                borderWidth: 2
            }]
        };

        // District Literacy Chart
        const districtCtx = document.getElementById('districtLiteracyChart').getContext('2d');
        new Chart(districtCtx, {
            type: 'bar',
            data: districtData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'District-Level Literacy Rates in Pakistan (2022/23)'
                    },
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Literacy Rate (%)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'District'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>

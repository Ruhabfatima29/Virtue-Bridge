<?php require_once __DIR__ . '/../controllers/creatorProjectsController.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Campaigns</title>
    <link rel="stylesheet" href="../../assets/css/creatorDashboard.css">
    <link rel="stylesheet" href="../../assets/css/myProjects.css">
    <style>
        .body{    
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        /* General container styling */
        .content-container {
            margin-left: 220px;
            padding: 40px;
            background-color:rgb(236, 249, 250);
            border-radius: 15px;
            box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            text-align: center;
        }

        /* Title styling */
        .content-container h2 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 2.5rem;
            color: #007b7e;
            margin-bottom: 30px;
            width: 100%;
        }

        /* Project cards container styling */
        .projects {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
            width: 100%;
        }

        /* Individual project card styling */
        .project-card {
            width: 100%;
            max-width: 400px;
            color: #0056b3;
            padding: 20px;
            margin-bottom: 30px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.1);
            text-align: left;
            transition: transform 0.3s ease-in-out;
        }

        .project-card:hover {
            transform: scale(1.05);
        }

        .project-card h3 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 1.8rem;
            color: #007b7e;
            margin-bottom: 15px;
        }

        .project-card p {
            font-size: 1rem;
            color: #555;
        }

        /* Progress bar container styling */
        .progress-bar-container {
            width: 100%;
            height: 25px;
            background-color: #e0e0e0;
            border-radius: 15px;
            margin: 10px 0;
        }

        /* Progress bar styling */
        .progress-bar {
            height: 100%;
            background-color: #4caf50;
            color: white;
            text-align: center;
            line-height: 25px;
            border-radius: 15px 0 0 15px;
            font-size: 16px;
        }

        /* Goal and Raised Amount Styling */
        .goal-amount,
        .raised-amount {
            font-weight: bold;
            font-size: 16px;
            color: #333;
            margin-top: 10px;
        }

        /* Status Styling */
        .status {
            font-size: 16px;
            font-weight: bold;
            color: #555;
            margin-top: 10px;
        }

        /* Button styling */
        .view-details-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }

        .view-details-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php include('../includes/creatorNavBar.php'); ?>
    <div class="main-content">
        <div class="content-container">
            <h2>My Campaigns</h2>

            <!-- Check if there are any projects -->
            <?php if (!empty($projects)) : ?>
                <div class="projects">
                    <?php foreach ($projects as $project) : ?>
                        <?php
                        $progress = $project['current_amount'] / $project['goal_amount'] * 100;
                        ?>
                        <div class="project-card">
                            <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                            <p class="status">Status: <?php echo htmlspecialchars($project['status']); ?></p>
                            <p><?php echo htmlspecialchars($project['description']); ?></p>

                            <!-- Progress Bar -->
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: <?php echo $progress; ?>%;">
                                    <?php echo round($progress) . "%"; ?>
                                </div>
                            </div>

                            <p class="goal-amount">Goal Amount: $<?php echo number_format($project['goal_amount'], 2); ?></p>
                            <p class="raised-amount">Raised Amount: $<?php echo number_format($project['current_amount'], 2); ?></p>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p>No campaigns found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>

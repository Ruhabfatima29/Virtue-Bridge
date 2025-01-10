<?php include('../includes/adminNavBar.php');
include('../controllers/addlocalCommunityController.php');
$communities = fetch_all_communities(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/creatorDashboard.css">
    <title>Manage Local Communities</title>
    <style>
        /* creatorDashboard.css */

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
            color: #333;
        }


        .main-content {
            margin-left: 260px;
            padding: 20px;
            flex-grow: 1;
            background-color: #f9f9f9;
            min-height: 100vh;
        }

        .main-content h1 {
            text-align: center;
            color: #007b7e;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        form button {
            background-color: rgb(2, 77, 78);
            color: #fff;
            border: none;
            cursor: pointer;
            padding: 10px;
            margin-bottom: 15px;
            margin-left: 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
        }

        table a {
            color: #4CAF50;
            text-decoration: none;
        }

        table a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- Main Content -->
    <div class="main-content">
        <?php
        $community = $_SESSION['edit_community'] ?? null;
        unset($_SESSION['edit_community']); // Clear the session variable after fetching
        ?>

        <h1>Add Local Communities</h1>
        <!-- Add/Edit Form -->
        <form action="../controllers/addlocalCommunityController.php" method="POST">
    <input type="hidden" name="community_id" value="<?php echo isset($community) ? $community['community_id'] : ''; ?>">

    <label for="name">Community Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $community['name'] ?? ''; ?>" required>

    <label for="description">Description:</label>
    <textarea id="description" name="description" required><?php echo $community['description'] ?? ''; ?></textarea>

    <label for="city">City:</label>
    <input type="text" id="city" name="city" value="<?php echo $community['city'] ?? ''; ?>" required>

    <label for="area">Area:</label>
    <input type="text" id="area" name="area" value="<?php echo $community['area'] ?? ''; ?>" required>

    <label for="literacy_rate">Literacy Rate:</label>
    <input type="number" id="literacy_rate" name="literacy_rate" step="0.01" value="<?php echo $community['literacy_rate'] ?? ''; ?>" required>

    <button type="submit" name="action" value="<?php echo isset($community) ? 'update' : 'add'; ?>">
        <?php echo isset($community) ? 'Update Community' : 'Add Community'; ?>
    </button>
</form>

<h1>Manage Local Communities</h1>

        <?php if (empty($communities)): ?>
            <p>No communities found.</p>
        <?php else: ?>
            <!-- Existing Communities Table -->
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>City</th>
                    <th>Area</th>
                    <th>Literacy Rate</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($communities as $community): ?>
                    <tr>
                        <td><?= $community['community_id']; ?></td>
                        <td><?= $community['name']; ?></td>
                        <td><?= $community['description']; ?></td>
                        <td><?= $community['city']; ?></td>
                        <td><?= $community['area']; ?></td>
                        <td><?= $community['literacy_rate']; ?></td>
                        <td>
                            <a href="../controllers/addlocalCommunityController.php?edit=<?= $community['community_id']; ?>">Edit</a>
                            <a href="../controllers/addlocalCommunityController.php?delete=<?= $community['community_id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>
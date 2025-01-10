<?php include('../includes/adminNavBar.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/creatorDashboard.css">
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(248, 248, 248);
            margin: 0;
            padding: 0;
        }

        .container {
            margin-left: 250px;
            max-width: calc(100% - 250px);
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .container h1,
        h2 {
            text-align: center;
            color: rgb(3, 119, 129);
        }

        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Add User Form */
        .add-user-form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .add-user-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: darkcyan;
        }

        .add-user-form input,
        .add-user-form select,
        .add-user-form button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .add-user-form button {
            background-color: rgb(34, 94, 117);
            color: #fff;
            cursor: pointer;
        }

        .add-user-form button:hover {
            background-color: rgb(2, 143, 156);
        }

        /* User Table */
        .user-table-container {
            max-width: 90%;
            margin: 20px auto;
            overflow-x: auto;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .user-table th,
        .user-table td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .user-table th {
            background-color: rgb(34, 94, 117);
            color: #fff;
            font-weight: bold;
        }

        .user-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .user-table tr:hover {
            background-color: #f1f1f1;
        }

        .inline-form {
            display: flex;
            align-items: center;
            gap: 10px;
            /* Slight gap between form elements */
            flex-wrap: nowrap;
            /* Prevent wrapping */
        }

        .inline-form input,
        .inline-form select,
        .inline-form button {
            font-size: 14px;
            padding: 5px 8px;
            border-radius: 5px;
        }

        .delete-btn {
            background: #d9534f;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            padding: 5px 10px;
            /* Add padding to button */
        }

        .delete-btn:hover {
            background: #c9302c;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Manage Users</h1>

        <!-- Display Messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message success"><?= $_SESSION['message'];
                                            unset($_SESSION['message']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="message error"><?= $_SESSION['error'];
                                        unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Add User Form -->
        <form action="../controllers/manageUsersController.php" method="post" class="add-user-form">
            <h2>Add User</h2>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="Username" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Password" required>

            <label for="role">Role</label>
            <select name="role" id="role" required>
                <option value="3">Admin</option>
                <option value="1">Creator</option>
            </select>

            <button type="submit" name="add_user">Add User</button>
        </form>

        <?php include '../controllers/manageUsersController.php'; ?>

        <!-- User Table -->
        <div class="user-table-container">
            <?php if ($result->num_rows > 0): ?>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Edit</th> <!-- Separate Edit Column -->
                            <th>Delete</th> <!-- Separate Delete Column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['user_id']) ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= $user['role'] == 3 ? 'Admin' : 'Creator'; ?></td>

                                <!-- Edit Column -->
                                <td>
                                    <form action="../controllers/manageUsersController.php" method="post" class="inline-form">
                                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                                        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                        <select name="role" required>
                                            <option value="1" <?= $user['role'] == 3 ? 'selected' : '' ?>>Admin</option>
                                            <option value="2" <?= $user['role'] == 1 ? 'selected' : '' ?>>Creator</option>
                                        </select>
                                        <button type="submit" name="edit_user">Save</button>
                                    </form>
                                </td>

                                <!-- Delete Column -->
                                <td>
                                    <form action="../controllers/manageUsersController.php" method="post" class="inline-form">
                                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                                        <button type="submit" name="delete_user" class="delete-btn">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-users">No users found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
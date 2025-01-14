<?php
session_start();
include '../db.php'; // Database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Delete message if delete action is triggered
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    // Delete the report from the database
    $delete_sql = "DELETE FROM messages WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        // Redirect back to the reports page
        header('Location: reports.php');
        exit();
    } else {
        // Handle error if the query fails
        die("Error deleting report: " . $conn->error);
    }
}

// Fetch messages
$sql = "SELECT m.id, u.name AS user_name, m.subject, m.message, m.sent_at
        FROM messages m
        JOIN users u ON m.user_id = u.id
        ORDER BY m.sent_at DESC";

// Execute the query
$result = $conn->query($sql);

// Check if the query executed successfully
if (!$result) {
    // Handle query error
    die("Error in query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f4f4f4;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 200px;
            height: 100vh;
            background-color: #333;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            position: fixed;
        }

        .sidebar h2 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 4px;
            display: block;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        /* Main Content Styles */
        .content {
            margin-left: 220px; /* Adjusted for sidebar */
            padding: 20px;
            width: calc(100% - 220px); /* Ensure full width minus the sidebar */
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .delete-button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        .delete-button:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="/cinemax/admin/dashboard.php">Dashboard</a>
    <a href="/cinemax/admin/admin.php">Manage Movies</a>
    <a href="/cinemax/admin/manage_users.php">Manage Users</a>
    <a href="/cinemax/admin/manage_admins.php">Manage Admins</a>
    <a href="/cinemax/admin/manage_bookings.php">Manage Bookings</a>
    <a href="/cinemax/admin/reports.php">Reports</a>
    <a href="/cinemax/admin/admin_logout.php">Logout</a>
</div>

<div class="content">
    <h1>Reports</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Sent At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['user_name']; ?></td>
                <td><?php echo $row['subject']; ?></td>
                <td><?php echo $row['message']; ?></td>
                <td><?php echo $row['sent_at']; ?></td>
                <td>
                    <!-- Delete button with link to trigger the delete action -->
                    <a href="reports.php?delete_id=<?php echo $row['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this report?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

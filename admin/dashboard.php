<?php
include '../db.php';

// Fetch total counts
$total_movies = $conn->query("SELECT COUNT(*) AS total FROM movies")->fetch_assoc()['total'];
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Movies</title>
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
    width: 200px; /* Reduced width from 250px to 200px */
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
            margin-left: 300px; /* Adjust to match the new sidebar width (200px + 20px padding) */
            padding: 20px;
            width: calc(100% - 220px); /* Dynamically adjusts to fit the remaining space */
}

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .movie {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .movie img {
            max-width: 200px;
            max-height: 300px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .edit-form {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .edit-form input {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
        }

        .content {
            margin-left: 20%;
            padding: 20px;
        }

        .dashboard-cards {
            display: flex;
            gap: 20px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1;
            text-align: center;
        }

        .card h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 36px;
            font-weight: bold;
            color: #007BFF;
        }
        .content {
            margin-left: 300px; /* Adjust to match the new sidebar width (200px + 20px padding) */
            padding: 20px;
            width: calc(100% - 220px);
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
    <a href="/cinemax/admin/logout.php">Logout</a>
</div>

<div class="content">
    <h1>Dashboard</h1>
    <div class="dashboard-cards">
        <div class="card">
            <h3>Total Movies</h3>
            <p><?php echo $total_movies; ?></p>
        </div>
        <div class="card">
            <h3>Total Users</h3>
            <p><?php echo $total_users; ?></p>
        </div>
        <div class="card">
            <h3>Total Bookings</h3>
            <p><?php echo $total_bookings; ?></p>
        </div>
    </div>
</div>

</body>
</html>
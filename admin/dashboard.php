<?php
include '../db.php';

session_start();

// Check if the admin is logged in, if not, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch admin details
$admin_id = $_SESSION['admin_id'];
$admin_query = $conn->prepare("SELECT name FROM admins WHERE id = ?");
$admin_query->bind_param("i", $admin_id);
$admin_query->execute();
$admin_result = $admin_query->get_result();

if ($admin_result->num_rows > 0) {
    $admin_name = $admin_result->fetch_assoc()['name'];
} else {
    $admin_name = "Admin"; // Default fallback
}

// Fetch total counts
$total_movies = $conn->query("SELECT COUNT(*) AS total FROM movies")->fetch_assoc()['total'];
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'];
$total_admins = $conn->query("SELECT COUNT(*) AS total FROM admins")->fetch_assoc()['total'];
$total_reports = $conn->query("SELECT COUNT(*) AS total FROM messages")->fetch_assoc()['total'];  // Added for reports
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            background-color: #f4f4f4;
        }

        /* Burger Icon */
        .burger-icon {
            display: none; /* Hidden by default */
            font-size: 24px;
            background: none;
            border: none;
            color: #007BFF;
            cursor: pointer;
            padding: 10px;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1000;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 200px;
            height: 100vh;
            background-color: #007BFF;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            transition: transform 0.3s ease;
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
            margin-left: 220px;
            padding: 20px;
            width: calc(100% - 220px);
            transition: margin-left 0.3s ease;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            margin-left: 25px;
        }

        .dashboard-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1 1 calc(50% - 20px);
            min-width: 200px;
            text-align: center;
            cursor: pointer;
        }

        .card h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 24px;
            font-weight: bold;
            color: #007BFF;
        }

        /* Add hover effect to cards */
        .card:hover {
            transform: scale(1.05);
        }

        /* Welcome Message Styles */
        .welcome-message {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: auto;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }

        .welcome-message.hidden {
            opacity: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .burger-icon {
                display: block; /* Show burger icon on smaller screens */
            }

            .sidebar {
                transform: translateX(-100%); /* Hide sidebar by default */
            }

            .sidebar.active {
                transform: translateX(0); /* Show sidebar when active */
            }

            .content {
                margin-left: 0;
                width: 100%;
            }

            .sidebar {
                width: 150px;
                padding: 10px;
            }

            .sidebar h2 {
                font-size: 20px;
            }

            .sidebar a {
                padding: 8px 10px;
                font-size: 14px;
            }

            h1 {
                font-size: 24px;
            }

            .card h3 {
                font-size: 16px;
            }

            .card p {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100px;
            }

            .sidebar h2 {
                font-size: 16px;
            }

            .sidebar a {
                font-size: 12px;
                padding: 5px 8px;
            }

            .content {
                margin-left: 110px;
                width: calc(100% - 110px);
                padding: 5px;
            }

            .dashboard-cards {
                flex-direction: column;
                gap: 10px;
            }

            .card {
                flex: 1 1 100%;
            }

            .card h3 {
                font-size: 14px;
            }

            .card p {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
<!-- Burger Icon -->
<button class="burger-icon" onclick="toggleSidebar()">☰</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
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
    <!-- Welcome Message -->
    <div id="welcomeMessage" class="welcome-message">
        Welcome, <?php echo htmlspecialchars($admin_name); ?>!
    </div>

    <h1>Dashboard</h1>
    <div class="dashboard-cards">
        <div class="card" onclick="window.location.href='admin.php';">
            <h3>Total Movies</h3>
            <p><?php echo $total_movies; ?></p>
        </div>
        <div class="card" onclick="window.location.href='manage_users.php';">
            <h3>Total Users</h3>
            <p><?php echo $total_users; ?></p>
        </div>
        <div class="card" onclick="window.location.href='manage_bookings.php'">
            <h3>Total Bookings</h3>
            <p><?php echo $total_bookings; ?></p>
        </div>
        <div class="card" onclick="window.location.href='manage_admins.php';">
            <h3>Total Admins</h3>
            <p><?php echo $total_admins; ?></p>
        </div>
        <!-- New Reports Card -->
        <div class="card" onclick="window.location.href='reports.php';">
            <h3>Total Reports</h3>
            <p><?php echo $total_reports; ?></p>
        </div>
    </div>
</div>

<script>
    // Toggle Sidebar
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }

    // Hide the welcome message after 3 seconds
    setTimeout(() => {
        const message = document.getElementById('welcomeMessage');
        message.classList.add('hidden');
        setTimeout(() => {
            message.style.display = 'none';
        }, 500); // Wait for transition to complete
    }, 3000);
</script>

</body>
</html>
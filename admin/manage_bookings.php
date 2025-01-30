<?php
include '../db.php';

// Fetch bookings data
$sql = "SELECT 
           CONCAT(u.name, ' - ', m.title) AS booking_details,
           b.booking_date
       FROM 
           bookings b
       INNER JOIN 
           users u ON b.user_id = u.id
       INNER JOIN 
           movies m ON b.movie_id = m.id
       ORDER BY 
           b.booking_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f4f4f4;
            overflow-x: hidden; /* Prevent horizontal scroll */
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
            font-size: 36px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            max-width: 100%; /* Ensure table doesn't overflow */
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        /* Responsive Styles */
        @media (max-width: 767px) {
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

            h1 {
                font-size: 28px;
                text-align: center;
            }

            table {
                display: block;
                overflow-x: auto; /* Allow table scrolling */
                white-space: nowrap; /* Prevent text wrapping in table cells */
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

<!-- Content -->
<div class="content">
    <h1>Manage Bookings</h1>
    <table>
        <tr>
            <th>Booking Details</th>
            <th>Booking Date</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['booking_details']; ?></td>
                    <td><?php echo $row['booking_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">No bookings found.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<script>
    // Toggle Sidebar
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }
</script>

</body>
</html>
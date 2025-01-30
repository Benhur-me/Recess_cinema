<?php
// Database connection
$host = 'localhost'; // Your DB host
$dbname = 'cinemax'; // Your DB name
$username = 'root'; // Your DB username
$password = ''; // Your DB password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch admins
$sql = "SELECT id, name, email FROM admins";
$result = $conn->query($sql);
$admins = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $admins[] = $row;
    }
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Admins</title>
    <style>
        /* Prevent horizontal scroll for the entire page */
        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Prevent horizontal scroll */
            font-family: Arial, sans-serif;
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

        .content {
            margin-left: 220px;
            padding: 20px;
            width: calc(100% - 220px);
            transition: margin-left 0.3s ease;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            text-align: left;
        }

        table {
            width: 100%;
            max-width: 100%; /* Ensure table doesn't overflow */
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            white-space: normal; /* Allow text wrapping */
        }

        table th {
            background-color: #f4f4f4;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container input, .form-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #4cae4c;
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
                width: 100%;
                max-width: 100%; /* Ensure table doesn't overflow */
            }

            table td {
                word-break: break-word; /* Break long words to prevent overflow */
            }

            table td:last-child {
                white-space: nowrap; /* Prevent wrapping for action buttons */
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
    <h1>Manage Admins</h1>

    <!-- Admin Management Table -->
    <table id="admins-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admins as $admin): ?>
                <tr>
                    <td><?= $admin['id'] ?></td>
                    <td><?= $admin['name'] ?></td>
                    <td><?= $admin['email'] ?></td>
                    <td>
                        <button onclick="deleteAdmin(<?= $admin['id'] ?>)">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add Admin Form -->
    <div class="form-container">
        <h2>Add New Admin</h2>
        <form id="add-admin-form" action="add_admin.php" method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Add Admin</button>
        </form>
    </div>
</div>

<script>
    // Toggle Sidebar
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }

    // Delete Admin
    function deleteAdmin(adminId) {
        if (confirm('Are you sure you want to delete this admin?')) {
            fetch('delete_admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: adminId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Admin deleted successfully!');
                    location.reload(); // Refresh the page
                } else {
                    alert('Error deleting admin: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting admin:', error);
                alert('There was an error deleting the admin.');
            });
        }
    }
</script>

</body>
</html>
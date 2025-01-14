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
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f4f4f4;
        }

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

        .content {
            margin-left: 300px;
            padding: 20px;
            width: calc(100% - 220px);
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
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
    <a href="/cinemax/admin/logout.php">Logout</a>
</div>

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

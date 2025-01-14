<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Users</title>
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
            margin-left: 220px;
            padding: 20px;
            width: calc(100% - 220px);
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            margin-left: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-left: 30px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
            
        }

        button {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #c9302c;
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
    <h1>Manage Users</h1>
    <table id="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Users will be dynamically loaded here -->
        </tbody>
    </table>
</div>

<script>
    // Fetch Users from the server
    function fetchUsers() {
        fetch('fetch_users.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#users-table tbody');
                tbody.innerHTML = ''; // Clear previous data
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5">No users found.</td></tr>';
                } else {
                    data.forEach(user => {
                        const row = `
                            <tr>
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>${user.phone}</td>
                                <td>
                                    <button onclick="deleteUser(${user.id})">Delete</button>
                                </td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching users:', error);
                alert('There was an error loading the user data.');
            });
    }

    // Delete User
    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            fetch('delete_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('User deleted successfully!');
                    fetchUsers(); // Refresh the user list
                } else {
                    alert('Error deleting user: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting user:', error);
                alert('There was an error deleting the user.');
            });
        }
    }

    // Load users when the page is loaded
    window.onload = fetchUsers;
</script>
</body>
</html>

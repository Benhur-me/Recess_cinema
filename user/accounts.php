<?php
include '../db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$user_query = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
if ($user_query === false) {
    die('Error preparing user query: ' . $conn->error);
}

$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
    $user_name = $user['name'];
    $user_email = $user['email'];
} else {
    echo "User not found.";
    exit();
}

// Fetch user's booking history without 'status' column
$bookings_query = $conn->prepare("SELECT bookings.booking_id, movies.title, bookings.booking_date, bookings.booking_time 
                                  FROM bookings 
                                  JOIN movies ON bookings.movie_id = movies.id 
                                  WHERE bookings.user_id = ?");
if ($bookings_query === false) {
    die('Error preparing bookings query: ' . $conn->error);
}

$bookings_query->bind_param("i", $user_id);
$bookings_query->execute();
$bookings_result = $bookings_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User - Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Raleway:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Sidebar styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: #007BFF;
            color: white;
            padding-top: 30px;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .sidebar a {
            display: block;
            padding: 15px;
            color: white;
            text-decoration: none;
            margin: 10px 0;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .sidebar a:hover {
            border-left: 3px solid #ff6f61;
            background-color: #575757;
        }

        /* Content area */
        .content {
            margin-left: 260px;
            padding: 20px;
            width: calc(100% - 260px);
            transition: margin-left 0.3s ease;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .account-info {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .account-info h3 {
            font-size: 24px;
        }

        .booking-history table {
            width: 100%;
            border-collapse: collapse;
        }

        .booking-history th, .booking-history td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        .booking-history th {
            background-color: #f4f4f4;
        }

        /* Burger Icon */
        .burger-icon {
            display: none;
            font-size: 24px;
            cursor: pointer;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            color: #007BFF;
        }

        /* Mobile responsiveness */
        @media screen and (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
            }

            .burger-icon {
                display: block;
            }

            h1 {
                font-size: 28px;
            }

            .account-info, .booking-history {
                padding: 15px;
            }

            .booking-history table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <!-- Burger Icon -->
    <div class="burger-icon" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="home.php">Home</a>
        <a href="index.php">Movies</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="accounts.php">Account</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main content -->
    <div class="content">
        <h1>Account - <?php echo htmlspecialchars($user_name); ?></h1>
        
        <!-- Account Information Section -->
        <div class="account-info">
            <h3>Account Details</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user_name); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?></p>
        </div>

        <!-- Booking History Section -->
        <div class="booking-history">
            <h3>Booking History</h3>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Movie Title</th>
                        <th>Booking Date</th>
                        <th>Booking Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($bookings_result->num_rows > 0): ?>
                        <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                                <td><?php echo htmlspecialchars($booking['title']); ?></td>
                                <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['booking_time']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div> 
    </div>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
    </script>
</body>
</html>
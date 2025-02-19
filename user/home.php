<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Raleway:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            color: #333;
        }

        /* Sidebar styling */
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

        /* Main content */
        .content {
            margin-left: 260px; /* Sidebar width */
            padding: 30px;
            background-color: #fff;
            flex-grow: 1;
            transition: margin-left 0.3s ease;
        }

        h1 {
            font-size: 36px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Raleway', Arial, sans-serif;
            font-weight: 600;
        }

        .box-shadow {
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .box-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.2);
        }

        .box-shadow h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 28px;
            font-weight: 500;
        }

        .box-shadow p {
            color: #777;
            font-size: 18px;
        }

        /* Footer */
        footer {
            background-color: #007BFF;
            color: white;
            text-align: center;
            padding: 15px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        footer p {
            margin: 0;
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

            .box-shadow {
                padding: 20px;
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

    <!-- Main Content -->
    <div class="content">
        <h1>Welcome to Online Cinema Booking</h1>

        <div class="box-shadow">
            <h3>Welcome to Online Cinema Booking</h3>
            <p>Your go-to platform for booking movie tickets online. We aim to make your movie experience as easy and enjoyable as possible!</p>
        </div>

        <div class="box-shadow">
            <h3>Our Mission</h3>
            <p>We strive to provide you with the latest movies, a seamless booking experience, and high-quality service to make your cinema visit unforgettable.</p>
        </div>

        <div class="box-shadow">
            <h3>Get Started</h3>
            <p>Browse through our extensive movie collection, select your favorite shows, and book your tickets in just a few clicks!</p>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Cinema Booking System</p>
    </footer>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
    </script>
</body>
</html>
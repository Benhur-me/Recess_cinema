<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Raleway:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
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
    background-color: #1f1f1f;
    color: white;
    padding-top: 30px;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
    border: px solid black;
}

        .sidebar a {
            display: block;
            padding: 15px;
            color: white;
            text-decoration: none;
            margin: 10px 0;
            border: px solid black;
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
            background-color: #1f1f1f;
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

        /* Mobile responsiveness */
        @media screen and (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 200px;
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

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="home.php">Home</a>
        <a href="index.php">Movies</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
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
</body>
</html>

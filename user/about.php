<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Raleway:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        /* Sidebar styling */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: #333;
            color: white;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            font-family: 'Raleway', Arial, sans-serif;
        }

        .sidebar a {
            display: block;
            padding: 15px;
            color: white;
            text-decoration: none;
            margin: 10px 0;
            font-size: 16px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #575757;
            color: #f4f4f4;
        }

        /* Main content */
        .content {
            margin-left: 260px; /* Sidebar width */
            padding: 20px;
            background-color: #fff;
            flex-grow: 1;
            font-family: 'Roboto', Arial, sans-serif;
        }

        h1 {
            font-size: 36px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Raleway', Arial, sans-serif;
            font-weight: 600;
        }

        p {
            font-size: 18px;
            color: #555;
            line-height: 1.6;
            text-align: justify;
            max-width: 800px;
            margin: 0 auto 20px;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
            margin-left: 260px; /* Align with content */
            width: calc(100% - 260px);
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            font-size: 14px;
            font-family: 'Raleway', Arial, sans-serif;
        }

        /* Mobile responsiveness */
        @media screen and (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 200px;
            }

            footer {
                margin-left: 200px;
                width: calc(100% - 200px);
            }

            h1 {
                font-size: 28px;
            }

            p {
                font-size: 16px;
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
        <h1>About Us</h1>
        <p>Welcome to Online Cinema Booking! Our mission is to make movie ticket booking as convenient and hassle-free as possible. We offer an easy-to-use platform where users can browse available movies, check showtimes, and book tickets with just a few clicks.</p>
        <p>Our team is committed to providing the best experience for movie lovers everywhere. Whether you're looking to catch the latest blockbuster or enjoy a classic film, we’ve got you covered. We aim to provide an intuitive, user-friendly system for all cinema-goers, making movie booking a breeze!</p>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Cinema Booking System</p>
    </footer>
</body>
</html>

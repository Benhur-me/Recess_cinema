<?php
session_start();  // Start session to manage login

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header('Location: login.php');
    exit();
}

include '../db.php';   // Adjust path to db.php

// Fetch user info
$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name);
$stmt->fetch();
$stmt->close();

// Extract initials
$initials = "";
if (!empty($name)) {
    $name_parts = explode(" ", $name);
    foreach ($name_parts as $part) {
        $initials .= strtoupper(substr($part, 0, 1));
    }
}

// Handle booking logic
if (isset($_POST['book_now'])) {
    $movie_id = $_POST['movie_id']; // Get the movie ID from the form submission

    // Fetch the price of the selected movie
    $movie_sql = "SELECT price FROM movies WHERE id = ?";
    $stmt = $conn->prepare($movie_sql);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();
    $stmt->close();

    // Insert booking information into the bookings table
    $booking_sql = "INSERT INTO bookings (user_id, movie_id) VALUES (?, ?)";
    $stmt = $conn->prepare($booking_sql);
    $stmt->bind_param("ii", $user_id, $movie_id);

    if ($stmt->execute()) {
        // Redirect after successful booking to prevent form resubmission on page refresh
        $_SESSION['booking_success'] = true;  // Store success flag in session
        $_SESSION['movie_price'] = $price;   // Store movie price in session
        header('Location: ' . $_SERVER['PHP_SELF']);  // Redirect to the same page
        exit();
    } else {
        $_SESSION['booking_error'] = true;  // Store error flag in session
        header('Location: ' . $_SERVER['PHP_SELF']);  // Redirect to the same page
        exit();
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema Booking System</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* Basic styling for the page */
        * {
            font-family: 'Roboto', Arial, sans-serif;
            font-style: normal;
            font-weight: 400;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: 1.5;
        }

        body {
            font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f4f4f4;
        }

        /* Sidebar Styles */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100%;
    background-color: #333;
    color: white;
    padding-top: 20px;
    border: px solid black;
}

        .sidebar a {
            display: block;
            padding: 15px;
            color: white;
            text-decoration: none;
            margin: 10px 0;
            border: px solid black;
        }

        .sidebar a:hover {
            background-color: #575757;
        }
        h3 {
            font-size: 24px;
            color: #333;
        }

        .movie {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .movie p {
            font-size: 18px;
            color: #555;
        }

        .movie img {
            max-width: 200px;
            max-height: 300px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .movie p, .movie h3 {
            margin: 10px 0;
        }

        /* Style for user initials in a circle */
        .user-initials {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background-color: #4CAF50;
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Style for the "Book Now" button */
        .book-now-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }

        .book-now-btn:hover {
            background-color: #45a049;
        }

        /* Styling for the alert message */
        .alert {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
            text-align: center;
            z-index: 9999;
            display: none;
        }

        /* Style for error alert */
        .alert.error {
            background-color: red;
        }

        /* Styling for movie list container */
        #movie-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .movie {
            flex: 1 1 300px;
            max-width: 300px;
            background-color: #fff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .movie img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            margin-top: 10px;
        }

        .book-now-btn {
            display: inline-block;
            margin-top: 10px;
        }

        nav ul {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            background-color: #333;
            overflow: hidden;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
        }

        nav ul li {
            flex: 1;
            text-align: center;
        }

        nav ul li a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: white;
            background-color: #333;
        }

        nav ul li a:hover {
            background-color: #555;
        }

        .logout-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            background-color: #d32f2f;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .logout-btn:hover {
            background-color: #b71c1c;
        }

        body, .sidebar, .movie, nav ul, .book-now-btn, .alert, .user-initials {
            font-family: "Roboto", Arial, sans-serif;
        }

        .sidebar {
            font-size: 16px;
            line-height: 1.5;
        }

        .movie {
            font-size: 14px;
            line-height: 1.6;
        }

        .book-now-btn, .logout-btn {
            font-family: "Roboto", Arial, sans-serif;
            font-weight: bold;
        }

  

/* Add padding to the main content section to avoid overlap with the sidebar */
#user-interface {
    margin-left: 270px; /* Adjust this to add space between the sidebar and the content */
    padding-right: 20px; /* Add space on the right side of the content */
}


/* Styling for movie list container */
#movie-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    margin-left: 20px; /* Adds a small gap between the sidebar and the content */
}


/* Ensure movie item does not stretch too much */
.movie {
    flex: 1 1 300px;
    max-width: 300px;
    background-color: #fff;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}


        @media screen and (max-width: 600px) {
            nav ul {
                flex-direction: column;
            }
        }

    </style>
</head>
<body>

<div class="sidebar">
        <a href="home.php">Home</a>
        <a href="index.php">Movies</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="user/logout.php">Logout</a>
    </div>

    <header>
        <center><h1>Online Cinema Booking</h1></center>
        <nav>
            <ul>
                <!-- Other menu items here -->
            </ul>
            
        </nav>
    </header>

    <!-- User Initials in a Circle -->
    <div class="user-initials">
        <?php echo $initials; ?>
    </div>

    <main>
        <section id="user-interface">
            <center><h2>Available Movies</h2></center>
            <div id="movie-list">
                <?php
                // Fetch all movies for users to view
                $sql = "SELECT * FROM movies";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Display the movie details
                        echo "<div class='movie'>";
                        echo "<h3>" . $row['title'] . "</h3>";
                        echo "<p>Show Time: " . $row['show_time'] . "</p>";
                        echo "<p>Price: UGX " . $row['price'] . "</p>";

                        // Check if the poster exists and display it
                        if (!empty($row['poster'])) {
                            // Adjust the path to the uploads folder
                            echo "<img src='../uploads/" . $row['poster'] . "' alt='" . $row['title'] . " Poster' width='200px'><br>";
                        } else {
                            echo "<p>No poster available.</p>";
                        }

                        // Add "Book Now" button for each movie
                        echo "<form method='POST' action=''>";
                        echo "<input type='hidden' name='movie_id' value='" . $row['id'] . "'>";
                        echo "<button type='submit' name='book_now' class='book-now-btn'>Book Now</button>";
                        echo "</form>";

                        echo "</div><br>";
                    }
                } else {
                    echo "<p>No movies available.</p>";
                }
                ?>
            </div>
        </section>
    </main>

    <footer>
        <center><p>&copy; 2024 Cinema Booking System</p></center>
    </footer>

    <!-- Booking Success Message -->
    <?php if (isset($_SESSION['booking_success']) && $_SESSION['booking_success']): ?>
        <div class="alert" id="bookingSuccessAlert">
            Booking successfully completed! Amount to pay: UGX <?php echo $_SESSION['movie_price']; ?>
        </div>
        <?php unset($_SESSION['booking_success']); ?>
    <?php endif; ?>

    <!-- Booking Error Message -->
    <?php if (isset($_SESSION['booking_error']) && $_SESSION['booking_error']): ?>
        <div class="alert error" id="bookingErrorAlert">
            Error in booking. Please try again.
        </div>
        <?php unset($_SESSION['booking_error']); ?>
    <?php endif; ?>

    <script>
        // Show the success alert when booking is successful
        <?php if (isset($_SESSION['booking_success']) && $_SESSION['booking_success']): ?>
            document.getElementById('bookingSuccessAlert').style.display = 'block';
            setTimeout(function() {
                document.getElementById('bookingSuccessAlert').style.display = 'none';
            }, 5000); // Hide the alert after 5 seconds
        <?php endif; ?>

        // Show the error alert when booking fails
        <?php if (isset($_SESSION['booking_error']) && $_SESSION['booking_error']): ?>
            document.getElementById('bookingErrorAlert').style.display = 'block';
            setTimeout(function() {
                document.getElementById('bookingErrorAlert').style.display = 'none';
            }, 5000); // Hide the alert after 5 seconds
        <?php endif; ?>
    </script>
</body>
</html>

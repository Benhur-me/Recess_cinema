<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header('Location: login.php');
    exit();
}

include('includes/db_connection.php');

// Fetch movies from the database to display for booking
$sql = "SELECT * FROM movies";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Movie</title>
</head>
<body>
    <h1>Book a Movie</h1>

    <h2>Available Movies</h2>
    <?php
    if ($result->num_rows > 0) {
        while ($movie = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h3>" . $movie['title'] . "</h3>";
            echo "<p>Show Time: " . $movie['show_time'] . "</p>";
            echo "<p>Price: UGX " . $movie['price'] . "</p>";
            echo "<a href='booking.php?movie_id=" . $movie['id'] . "'>Book Now</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No movies available to book.</p>";
    }
    ?>

</body>
</html>

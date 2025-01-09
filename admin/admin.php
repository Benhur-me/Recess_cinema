<?php
include '../db.php';

// Handle movie deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    if (filter_var($delete_id, FILTER_VALIDATE_INT)) {
        $sql = "DELETE FROM movies WHERE id = $delete_id";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Movie deleted successfully!</p>";
        } else {
            echo "<p>Error deleting the movie: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>Invalid movie ID.</p>";
    }
}

// Handle movie edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_movie'])) {
    // Get form data
    $movie_id = $_POST['movie_id'];
    $title = $_POST['title'];
    $show_time = $_POST['show_time'];
    $price = $_POST['price'];
    $poster = $_FILES['poster'];

    // If a new poster is uploaded, handle the file upload
    if ($poster['name'] != '') {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($poster['name']);
        if (move_uploaded_file($poster['tmp_name'], $target_file)) {
            $poster_filename = basename($poster['name']);
        } else {
            echo "<p>Error uploading the poster.</p>";
            $poster_filename = ''; // If no new poster uploaded, keep the old one
        }
    } else {
        // If no new poster, keep the old one
        $poster_filename = $_POST['existing_poster'];
    }

    // Update movie details
    $sql_update = "UPDATE movies SET title = '$title', show_time = '$show_time', price = '$price', poster = '$poster_filename' WHERE id = $movie_id";

    if ($conn->query($sql_update) === TRUE) {
        echo "<p>Movie updated successfully!</p>";
    } else {
        echo "<p>Error updating movie: " . $conn->error . "</p>";
    }
}

// Handle adding a new movie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_movie'])) {
    $title = $_POST['title'];
    $show_time = $_POST['show_time'];
    $price = $_POST['price'];
    $poster = $_FILES['poster'];

    // Handle poster upload
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($poster['name']);
    if (move_uploaded_file($poster['tmp_name'], $target_file)) {
        $poster_filename = basename($poster['name']);
        // Insert movie details into the database
        $sql_insert = "INSERT INTO movies (title, show_time, price, poster) VALUES ('$title', '$show_time', '$price', '$poster_filename')";
        if ($conn->query($sql_insert) === TRUE) {
            echo "<p>New movie added successfully!</p>";
        } else {
            echo "<p>Error adding the movie: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>Error uploading the poster.</p>";
    }
}

// Fetch all movies for display
$sql = "SELECT * FROM movies";
$result = $conn->query($sql);

// Fetch movie details for editing
$movie_to_edit = null;
if (isset($_GET['edit_id'])) {
    $movie_id = $_GET['edit_id'];
    $sql_edit = "SELECT * FROM movies WHERE id = $movie_id";
    $movie_to_edit = $conn->query($sql_edit)->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Movies</title>
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

        h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .movie {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .movie img {
            max-width: 200px;
            max-height: 300px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .edit-form {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .edit-form input {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
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
    <a href="/cinemax/admin/admin_logout.php">Logout</a>
</div>

<div class="content">
    <h1>Admin Panel - Manage Movies</h1>

    <h2>Add a Movie</h2>
    <form method="POST" action="admin.php" enctype="multipart/form-data">
        <label for="title">Movie Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="show_time">Show Time:</label><br>
        <input type="datetime-local" id="show_time" name="show_time" required><br><br>

        <label for="price">Price (UGX):</label><br>
        <input type="number" id="price" name="price" step="0.01" required><br><br>

        <label for="poster">Movie Poster:</label><br>
        <input type="file" id="poster" name="poster" required><br><br>

        <button type="submit" name="add_movie" class="button">Add Movie</button>
    </form>

    <h2>Edit Movie</h2>
    <?php
    if ($movie_to_edit) {
        echo "<form method='POST' action='admin.php' enctype='multipart/form-data'>";
        echo "<input type='hidden' name='movie_id' value='" . $movie_to_edit['id'] . "'>";
        echo "<input type='hidden' name='existing_poster' value='" . $movie_to_edit['poster'] . "'>";
        echo "<label for='title'>Movie Title:</label><br>";
        echo "<input type='text' id='title' name='title' value='" . $movie_to_edit['title'] . "' required><br><br>";
        echo "<label for='show_time'>Show Time:</label><br>";
        echo "<input type='datetime-local' id='show_time' name='show_time' value='" . $movie_to_edit['show_time'] . "' required><br><br>";
        echo "<label for='price'>Price (UGX):</label><br>";
        echo "<input type='number' id='price' name='price' value='" . $movie_to_edit['price'] . "' step='0.01' required><br><br>";
        echo "<label for='poster'>Movie Poster:</label><br>";
        echo "<input type='file' id='poster' name='poster'><br><br>";
        echo "<button type='submit' name='edit_movie' class='button'>Update Movie</button>";
        echo "</form>";
    }
    ?>

    <h2>Current Movies</h2>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='movie'>";
            echo "<h3>" . $row['title'] . "</h3>";
            echo "<p>Show Time: " . $row['show_time'] . "</p>";
            echo "<p>Price: UGX " . $row['price'] . "</p>";
            if (!empty($row['poster'])) {
                echo "<img src='../uploads/" . $row['poster'] . "' alt='" . $row['title'] . " Poster'><br>";
            } else {
                echo "<p>No poster available.</p>";
            }
            echo "<a href='admin.php?edit_id=" . $row['id'] . "' class='button'>Edit</a> ";
            echo "<a href='admin.php?delete_id=" . $row['id'] . "' class='button' onclick='return confirm(\"Are you sure you want to delete this movie?\");'>Delete</a>";
            echo "</div><br>";
        }
    } else {
        echo "<p>No movies available.</p>";
    }
    ?>
</div>

</body>
</html>

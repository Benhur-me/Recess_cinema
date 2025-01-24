<?php
session_start();
include '../db.php'; // Database connection

$message_status = ""; // Initialize status message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $subject = $_POST['subject'] ?? null;
    $message = $_POST['message'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null; // Assuming user_id is stored in the session after login

    // Validate required fields
    if (!$subject || !$message) {
        $message_status = "All fields are required.";
    } else if (!$user_id) {
        $message_status = "User not logged in.";
    } else {
        // Prepare SQL query
        $sql = "INSERT INTO messages (user_id, subject, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Check if preparation succeeded
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error); // Debugging output
        }

        // Bind parameters
        $stmt->bind_param("iss", $user_id, $subject, $message);

        // Execute the statement
        if ($stmt->execute()) {
            $message_status = "Message sent successfully!";
        } else {
            $message_status = "Error executing query: " . $stmt->error; // Debugging output
        }

        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
            background-color: #007BFF;
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
            padding: 20px;
            background-color: #fff;
            flex-grow: 1;
        }

        h1 {
            font-size: 36px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 15px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        button:hover {
            background-color: #575757;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            margin-left: 260px; /* Align with content */
            width: calc(100% - 260px);
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
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

            form {
                padding: 15px;
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
        <a href="accounts.php">Account</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Contact Us</h1>
        
        <?php if ($message_status): ?>
            <script>
                alert("<?php echo addslashes($message_status); ?>");
            </script>
        <?php endif; ?>
        
        <form action="contact.php" method="POST">
            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required> 

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" required></textarea> 

            <button type="submit">Send Message</button>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Cinema Booking System</p>
    </footer>
</body>
</html>

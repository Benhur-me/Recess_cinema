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
    <title>Contact Us</title>
    <script>
        // JavaScript to display the alert
        function showAlert(message) {
            if (message) {
                alert(message);
            }
        }
    </script>
</head>
<body onload="showAlert('<?php echo addslashes($message_status); ?>')">
    <h3>Contact Us</h3>
    <form action="send_message.php" method="POST">
        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="5" required></textarea>

        <button type="submit">Send Message</button>
    </form>
</body>
</html>

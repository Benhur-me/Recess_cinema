<?php
// Include the database connection file
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form fields are set
    if (isset($_POST['email'], $_POST['password'], $_POST['confirm_password'], $_POST['name'], $_POST['phone'])) {
        // Get form data
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $name = $_POST['name'];  // Get the name
        $phone = $_POST['phone'];  // Get the phone number

        // Check if passwords match
        if ($password != $confirm_password) {
            echo "Passwords do not match!";
            exit;
        }

        // If passwords match, continue with the registration
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL query to insert data (including name and phone)
        $sql = "INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql); // Prepare the query

        if ($stmt === false) {
            echo "Error preparing the query: " . $conn->error;
            exit;
        }

        // Bind parameters (name, email, password, phone)
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $phone);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to the login page after successful registration
            header("Location: login.php");
            exit; // Make sure the rest of the script doesn't execute
        } else {
            echo "Error executing the statement: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "All fields are required!";
    }
}
?>









<!-- Registration Form -->
<form action="register.php" method="POST">
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    Confirm Password: <input type="password" name="confirm_password" required><br>
    Name: <input type="text" name="name" required><br> <!-- Ensure name field is present -->
    Phone: <input type="text" name="phone" required><br> <!-- Ensure phone field is present -->
    <input type="submit" value="Register">
</form>






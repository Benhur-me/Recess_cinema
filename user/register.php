<?php
// Include the database connection file
include '../db.php';

$error_message = ""; // Variable to store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'], $_POST['password'], $_POST['confirm_password'], $_POST['name'], $_POST['phone'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];

        // Validate passwords
        if ($password != $confirm_password) {
            $error_message = "Passwords do not match!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data into the database
            $sql = "INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                $error_message = "Error preparing the query: " . $conn->error;
            } else {
                $stmt->bind_param("ssss", $name, $email, $hashed_password, $phone);

                if ($stmt->execute()) {
                    // Redirect to login page on success
                    header("Location: login.php");
                    exit;
                } else {
                    $error_message = "Error executing the statement: " . $stmt->error;
                }

                $stmt->close();
            }

            $conn->close();
        }
    } else {
        $error_message = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(120deg, #a1c4fd, #c2e9fb);
            animation: backgroundShift 5s infinite alternate;
        }

        @keyframes backgroundShift {
            0% {
                background: linear-gradient(120deg, #f6d365, #fda085);
            }
            100% {
                background: linear-gradient(120deg, #a1c4fd, #c2e9fb);
            }
        }

        .container {
            display: flex;
            width: 90%;
            max-width: 1000px;
            background: white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            overflow: hidden;
            transform: translateY(20px);
            animation: slideIn 1s ease-out forwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(100px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .left-section {
            flex: 1;
            background: #007BFF;
            color: white;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            animation: fadeIn 2s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .left-section h2 {
            font-size: 2.5em;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .left-section p {
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 20px;
            color: #fff;
        }

        .form-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        form {
            width: 100%;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8em;
            color: #555;
        }

        .error-message {
            color: #d9534f;
            background-color: #f9d6d5;
            border: 1px solid #d9534f;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
            background: #f9f9f9;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
            background: #ffffff;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            margin-top: 15px;
            color: #666;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        /* Mobile responsiveness */
        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
                width: 90%;
                margin: 20px;
            }

            .left-section {
                padding: 20px;
            }

            .left-section h2 {
                font-size: 2em;
            }

            .left-section p {
                font-size: 1em;
            }

            .form-section {
                padding: 20px;
            }

            h1 {
                font-size: 1.5em;
            }

            input[type="email"], input[type="password"], input[type="text"] {
                padding: 10px;
                font-size: 0.9em;
            }

            button {
                padding: 10px;
                font-size: 0.9em;
            }

            p {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <h2>Welcome to Registration</h2>
            <p>Create your account to enjoy exclusive benefits and stay connected with us.</p>
            <p>Already have an account? <a href="login.php" style="color: white; text-decoration: underline;">Login here</a>.</p>
        </div>
        <div class="form-section">
            <form action="register.php" method="POST">
                <h1>Register</h1>
                <?php if (!empty($error_message)): ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <label>Email:</label>
                <input type="email" name="email" placeholder="Enter your email" required>
                <label>Password:</label>
                <input type="password" name="password" placeholder="Enter your password" required>
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                <label>Name:</label>
                <input type="text" name="name" placeholder="Enter your full name" required>
                <label>Phone:</label>
                <input type="text" name="phone" placeholder="Enter your phone number" required>
                <button type="submit">Register</button>
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </form>
        </div>
    </div>
</body>
</html>
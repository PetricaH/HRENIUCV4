<?php

// Database connection
$host = 'localhost';
$dbname = 'hreniucv4';
$username = 'root';
$password = 'RO545389###';

try { 
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
        // Sanitize and validate email
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $isValidEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($isValidEmail) {
            // Check if the email already exists in the database
            $checkStmt = $pdo->prepare("SELECT * FROM newsletter_subscribers WHERE email = :email");
            $checkStmt->bindParam(':email', $email);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() == 0) {
                // Prepare an insert statement
                $stmt = $pdo->prepare("INSERT INTO newsletter_subscribers (email) VALUES (:email)");
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                echo "Thank you for subscribing!";
            } else {
                echo "This email is already subscribed.";
            }
        } else {
            echo "Invalid email address.";
        }
    }
} catch (PDOException $e) {
    echo "Connection Failed: " . $e->getMessage();
}
?>

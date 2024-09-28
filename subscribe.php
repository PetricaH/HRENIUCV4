<?php
include('config.php');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Database connected successfully.";
}
?>
<?php

// database connection
$host = 'localhost';
$dbname = 'hreniucv4';
$username = 'root';
$password = 'RO545389###';

try { 
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

        if ($email) {
            // prepare an insert statement
            $stmt = $pdo->prepare("INSERT INTO newsletter_subscribers (email) VALUES (:email)");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            echo "Thank you for subscribing!";
        } else {
            echo "Invalid email adress.";
        }
    }
} catch (PDOException $e) {
    echo "Connection Failed: " . $e->getMessage();
}
?>
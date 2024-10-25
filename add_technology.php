<?php
require_once('../config.php');

if (isset($_POST['tech_name']) && isset($_FILES['tech_logo'])) {
    $tech_name = $_POST['tech_name'];
    $logo = $_FILES['tech_logo'];

    // Handle file upload
    $target_dir = "uploads/technologies/";
    $target_file = $target_dir . basename($logo["name"]);
    
    if (move_uploaded_file($logo["tmp_name"], $target_file)) {
        // Insert into database
        $query = "INSERT INTO technologies (name, logo) VALUES ('$tech_name', '$target_file')";
        if (mysqli_query($conn, $query)) {
            echo "Technology added successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

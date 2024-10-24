<?php 

// Initialize variables

use LDAP\Result;

$isEditingWebDev = false;
$webdev_id = 0;
$title = "";
$description = "";
$project_category_id = "";
$published = false;
$project_image = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $conn;
    
    // file upload handling 
    $target_dir = "uploads/projects";
    $target_file = $target_dir . basename($_FILES["project_image"]["name"]);
    move_uploaded_file($_FILES["project_image"]["tmp_name"], $target_file);

    // insert project data into database
    $title = $_POST['title'];
    $description = $_POST['description'];
    $technologies = $_POST['technologies'];
    $image = $target_file;
    
    $stmt = $conn->prepare("INSERT INTO projects (title, description, image, technologies) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $image, $technologies);
    $stmt->execute();
    
    echo "New webdev project added successfully!";
    $stmt->close();
    $conn->close();
}

?>


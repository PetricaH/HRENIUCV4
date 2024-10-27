<?php
include('../config.php'); // Adjust the path as needed

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tech_name']) && isset($_FILES['tech_logo'])) {
    $tech_name = $_POST['tech_name'];
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/HRENIUCV4/uploads/tech_logos/";
    $target_file = $target_dir . basename($_FILES["tech_logo"]["name"]);

    if (move_uploaded_file($_FILES["tech_logo"]["tmp_name"], $target_file)) {
        $logo_path = 'uploads/tech_logos/' . basename($_FILES["tech_logo"]["name"]);
        $query = "INSERT INTO technologies (name, logo) VALUES ('$tech_name', '$logo_path')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Technology added successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Error adding technology.";
            $_SESSION['msg_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Error uploading file.";
        $_SESSION['msg_type'] = "error";
    }
    header("Location: add_technology.php"); // Redirect to avoid duplicate insertions on refresh
    exit();
}
?>
<?php
include('../config.php'); 
include(ROOT_PATH . '/admin/includes/admin_functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tech_id'])) {
    $techId = $_POST['tech_id'];
    deleteTechnology($techId);
}

// Redirect back to the add_technology.php page after deletion
header("Location: add_technology.php");
exit();
?>

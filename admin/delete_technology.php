<?php
include('../config.php'); // includes configuration file in database connection setup

// check if the request method is POST and the 'tech_id' field is set in the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tech_id'])) {
    // sanitize and validate the technology ID by casting it to an integer
    $tech_id = (int)$_POST['tech_id'];
    
    // prepare SQL query to delete the technology with the specified ID 
    $query = "DELETE FROM technologies WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query); // prepare the SQL statement for execution
    mysqli_stmt_bind_param($stmt, 'i', $tech_id); // bind the $tech_id to the SQL statment as an integer ('i')

    // execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // set a success messge in the session to notify the user of successfull deletion
        $_SESSION['message'] = "Technology deleted successfully!";
        $_SESSION['msg_type'] = "success";
    } else {
        // set an error message if the deletion fails
        $_SESSION['message'] = "Error deleting technology.";
        $_SESSION['msg_type'] = "error";
    }

    // close the prepared statement to free up resources
    mysqli_stmt_close($stmt);

    // redirect back to the add_technology.php after processing
    header("Location: add_technology.php");
    exit(); // ensure no further code runs after the redirect
} else {
    // set an error message if the request is not valid (if 'tech_id' was missing)
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['msg_type'] = "error";

    // redirect back to the add_technology.php after processing
    header("Location: add_technology.php");
    exit(); 
}
?>

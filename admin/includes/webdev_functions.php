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

// Add a new web development project with selected technologies
function addProject($title, $description, $imagePath, $technologyIds) {
    global $conn;

    // Insert the project details into the webdev_projects table
    $query = "INSERT INTO projects (title, description, image, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $title, $description, $imagePath);

    if (mysqli_stmt_execute($stmt)) {
        $projectId = mysqli_insert_id($conn);
        
        // Link the selected technologies with the project in the project_technologies table
        foreach ($technologyIds as $techId) {
            $techQuery = "INSERT INTO technologies (name, logo) VALUES (?, ?)";
            $techStmt = mysqli_prepare($conn, $techQuery);
            mysqli_stmt_bind_param($techStmt, "ii", $projectId, $techId);
            mysqli_stmt_execute($techStmt);
        }

        mysqli_stmt_close($stmt);
        return true;
    } else {
        return false;
    }
}

// Fetch the latest published projects with associated technologies
function getPublishedWebdevProjects() {
    global $conn;
    $query = "SELECT * FROM projects ORDER BY created_at DESC LIMIT 3";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC); // Ensure this returns an array
}
?>


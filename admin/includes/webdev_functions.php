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

// Fetch all available technologies
function getAllTechnologies() {
    global $conn;
    $query = "SELECT * FROM technologies";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Add a new web development project with selected technologies
function addProject($title, $description, $imagePath, $technologyIds) {
    global $conn;

    // Insert the project details into the webdev_projects table
    $query = "INSERT INTO webdev_projects (title, description, project_image, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $title, $description, $imagePath);

    if (mysqli_stmt_execute($stmt)) {
        $projectId = mysqli_insert_id($conn);
        
        // Link the selected technologies with the project in the project_technologies table
        foreach ($technologyIds as $techId) {
            $techQuery = "INSERT INTO project_technologies (project_id, technology_id) VALUES (?, ?)";
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
    $query = "
        SELECT wp.*, t.name AS tech_name, t.logo AS tech_logo 
        FROM webdev_projects wp
        LEFT JOIN project_technologies pt ON wp.id = pt.project_id
        LEFT JOIN technologies t ON pt.tech_id = t.id
        ORDER BY wp.created_at DESC LIMIT 3";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    }

    $projects = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $project_id = $row['id'];
        if (!isset($projects[$project_id])) {
            $projects[$project_id] = $row;
            $projects[$project_id]['technologies'] = [];
        }
        $projects[$project_id]['technologies'][] = [
            'name' => $row['tech_name'],
            'logo' => $row['tech_logo']
        ];
    }
    return $projects;
}

?>


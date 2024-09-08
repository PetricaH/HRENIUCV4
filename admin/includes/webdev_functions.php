<?php 

// initialize variables
$isEditingProject = false;
$project_id = 0;
$title = "";
$description = "";
$category_id = "";
$published = false;
$project_image = "";

// 

// function to get all web development projects from the webdev table
function getAllWebdevProjects() {
    global $conn;
    $sql = "SELECT w.*, c.name as category FROM webdev w
            LEFT JOIN webdev_category_id = c.id
            ON w.project_category_id = c.id
            ORDER BY w.created_at DESC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $projects;
}

// function to get all categories for web development projects
function getAllWebdevCategories() {
    global $conn;
    $sql = "SELECT * FROM webdev_project_categories";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $categories;
}

// function to handle project craeation or editing
if (isset($_POST['save_project'])) {
    $project_id = isset($_POST['project_id']) ? $_POST['project_id'] : 0;
    $title = esc($_POST['title']);
    $description = esc($_POST['description']);
    $category_id = isset($_POST['category_id']) ? esc($_POST['category_id']) : null;
    $published = isset($_POST['publish']) ? 1 : 0;

    // handle image upload
    if (isset($_FILES['project_image']['name']) && $_FILES['project_image']['name'] != "") {
        $project_image = time() . '_' . $_FILES['project_image']['name'];
        $upload_dir = ROOT_PATH . "/uploads/projects/";

        // check if the directory exists, if not, create it
        if(!$file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true); // creates the directory with write permissions
        }

        $target = $upload_dir . $project_image;

        // move the uploaded fiele to the target directory
        if (!move_uploaded_file($_FILES['project_image']['tmp_name'], $target)) {
            die("Failed to upload image");
        }
    } else {
        // keep the existing image if editing and no new image is uploaded
        if ($isEditingProject) {
            $sql = "SELECT project_image FROM webdev WHERE id ='$project_id' LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $project = mysqli_fetch_assoc($result);
            $project_image = $project['project_image']; 
        }
    }

    if ($isEditingProject) {
        // update the project record
        $sql = "UPDATE webdev SET title='$title', description='$description', project_category_id='$category_id', published='$published', project_image='$project_image', project_id='$project_id'";
        if (!mysqli_query($conn, $sql)) {
            die("Query failed: " . mysqli_error($conn));
        } else {
            $_SESSION['message'] = "Project uploaded successfully";
            header('Location: manage_webdev.php'); // redirect after update (needed so that the projects do not get cloned)
            exit(0);
        }
    } else {
        // insert new project record
        $sql = "INSERT INTO webdev (title, description, project_category_id, publihsed, project_image) VALUES ('$title', '$description', '$category_id', '$published', '$project_image')";
        if (!mysqli_query($conn, $sql)) {
            die("Query failed: " . mysqli_error($conn));
        } else {
            $_SESSION['message'] = "Project created successfully";
            header('Location: manage_webdev.php'); // redirect after creation (same as for the update)
            exit(0);
        }
    }
}

// function to escape from form inputs 
if (!function_exists('esc')) {
    function esc(String $value) {
        global $conn;
        $val = trim($value);
        return mysqli_real_escape_string($conn, $val);
    }
}

// function to delete web dev projects
function deleteWebdevProject($project_id) {
    global $conn;
    
    // first, get  the project to delete the image file
    $sql = "SELECT project_image FROM webdev WHERE id=$project_id";
    $result = mysqli_query($conn, $sql);
    $project = mysqli_fetch_assoc($result);

    if ($project) {
        $project_image = $project['project_image'];
        $image_path = ROOT_PATH . "/uploads/projects/" . $project_image;

        // delet the project image file if it exists
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // next step, delete the project record from the database
        $sql = "DELETE FROM webdev WHERE id=$project_id LIMIT 1";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Project successfully deleted";
            header("Location: manage_webdev.php");
            exit(0);
        } else {
            $_SESSION['message'] = "Failed to delete project";
        }
    }
}

?>
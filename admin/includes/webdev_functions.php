<?php 

// Initialize variables
$isEditingWebDev = false;
$webdev_id = 0;
$title = "";
$description = "";
$project_category_id = "";
$published = false;
$project_image = "";

// Function to get all web development projects from the webdev table
function getAllWebDevProjects() {
    global $conn;
    $sql = "SELECT w.*, c.name as category FROM webdev w LEFT JOIN webdev_project_categories c ON w.project_category_id = c.id ORDER BY w.created_at DESC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $webdevprojects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $webdevprojects;
}

// Function to handle web development project creation or editing
if (isset($_POST['save_webdev'])) {
    $webdev_id = isset($_POST['webdev_id']) ? $_POST['webdev_id'] : 0;
    $title = esc($_POST['title']);
    $description = esc($_POST['description']);
    $project_category_id = isset($_POST['project_category_id']) ? esc($_POST['project_category_id']) : null;
    $published = isset($_POST['publish']) ? 1 : 0;

    // Handle image upload
    if (isset($_FILES['project_image']['name']) && $_FILES['project_image']['name'] != "") {
        $project_image = time() . '_' . $_FILES['project_image']['name'];
        $upload_dir = ROOT_PATH . "/uploads/webdev/";

        // Check if the directory exists, if not, create it
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Creates the directory with write permissions
        }

        $target = $upload_dir . $project_image;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES['project_image']['tmp_name'], $target)) {
            die("Failed to upload image");
        }

    } else {
        // Keep the existing image if editing and no new image is uploaded
        if ($isEditingWebDev) {
            $sql = "SELECT project_image FROM webdev WHERE id='$webdev_id' LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $webdev = mysqli_fetch_assoc($result);
            $project_image = $webdev['project_image'];
        }
    }

    if ($isEditingWebDev) {
        // Update the webdev project record
        $sql = "UPDATE webdev SET title='$title', description='$description', project_category_id='$project_category_id', published='$published', project_image='$project_image' WHERE id='$webdev_id'";
        if (!mysqli_query($conn, $sql)) {
            die("Query failed: " . mysqli_error($conn));
        } else {
            $_SESSION['message'] = "Web Development Project updated successfully";
            header('Location: manage_webdev.php');
            exit(0);
        }
    } else {
        // Insert new webdev project record
        $sql = "INSERT INTO webdev (title, description, project_category_id, published, project_image) VALUES ('$title', '$description', '$project_category_id', '$published', '$project_image')";
        if (!mysqli_query($conn, $sql)) {
            die("Query failed: " . mysqli_error($conn));
        } else {
            $_SESSION['message'] = "Web Development Project created successfully";
            header('Location: manage_webdev.php');
            exit(0);
        }
    }
}

// Function to escape form inputs (only declare if it hasn't been declared yet)
if (!function_exists('esc')) {
    function esc(String $value) {
        global $conn;
        $val = trim($value);
        return mysqli_real_escape_string($conn, $val);
    }
}

// Function to delete web development projects
function deleteWebDevProject($webdev_id) {
    global $conn;

    // First, get the project to delete the image file
    $sql = "SELECT project_image FROM webdev WHERE id=$webdev_id";
    $result = mysqli_query($conn, $sql);
    $webdev = mysqli_fetch_assoc($result);

    if ($webdev) {
        $project_image = $webdev['project_image'];
        $image_path = ROOT_PATH . "/uploads/webdev/" . $project_image;

        // Delete the project image file if it exists
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Now, delete the project record from the database
        $sql = "DELETE FROM webdev WHERE id=$webdev_id LIMIT 1";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Web Development Project successfully deleted";
            header("Location: manage_webdev.php");
            exit(0);
        } else {
            $_SESSION['message'] = "Failed to delete project";
        }
    }
}

?>

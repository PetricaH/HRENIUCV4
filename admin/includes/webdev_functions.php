<?php 

// Initialize variables
$isEditingWebDevPost = false;
$webdev_post_id = '';
$title = '';
$description = '';
$published = false;
$project_image = '';

// Function to generate slug
function makeSlug($title) {
    // Replace spaces with hyphens and convert to lowercase
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
}

// Function to get all web development projects
function getAllWebdevProjects() {
    global $conn;
    $sql = "SELECT w.*, c.name as category 
            FROM webdev w 
            LEFT JOIN webdev_project_categories c 
            ON w.project_category_id = c.id 
            ORDER BY w.created_at DESC";
    
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $projects;
}

// Function to handle project creation or editing
if (isset($_POST['save_project'])) {
    $project_id = isset($_POST['project_id']) ? $_POST['project_id'] : 0;
    $title = esc($_POST['title']);
    $description = esc($_POST['description']);
    $category_id = isset($_POST['category_id']) ? esc($_POST['category_id']) : null;
    $published = isset($_POST['publish']) ? 1 : 0;

    // Generate slug
    $slug = makeSlug($title);

    // Handle image upload
    if (isset($_FILES['project_image']['name']) && $_FILES['project_image']['name'] != "") {
        $project_image = time() . '_' . $_FILES['project_image']['name'];
        $upload_dir = ROOT_PATH . "/uploads/projects/";

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
        if ($isEditingWebDevPost) {
            $sql = "SELECT project_image FROM webdev WHERE id='$project_id' LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $project = mysqli_fetch_assoc($result);
            $project_image = $project['project_image'];
        }
    }

    if ($isEditingWebDevPost) {
        // Update the project record
        $sql = "UPDATE webdev SET title='$title', description='$description', project_category_id='$category_id', published='$published', project_image='$project_image', slug='$slug' WHERE id='$project_id'";
        if (!mysqli_query($conn, $sql)) {
            die("Query failed: " . mysqli_error($conn));
        } else {
            $_SESSION['message'] = "Project updated successfully";
            header('Location: manage_webdev_post.php'); // Redirect after update
            exit(0);
        }
    } else {
        // Insert new project record
        $sql = "INSERT INTO webdev (title, description, project_category_id, published, project_image, slug) VALUES ('$title', '$description', '$category_id', '$published', '$project_image', '$slug')";
        if (!mysqli_query($conn, $sql)) {
            die("Query failed: " . mysqli_error($conn));
        } else {
            $_SESSION['message'] = "Project created successfully";
            header('Location: manage_webdev_post.php'); // Redirect after creation
            exit(0);
        }
    }
}

// Function to escape form inputs
if (!function_exists('esc')) {
    function esc(String $value) {
        global $conn;
        $val = trim($value);
        return mysqli_real_escape_string($conn, $val);
    }
}

// Function to delete web dev projects
function deleteWebdevProject($project_id) {
    global $conn;
    
    // First, get the project to delete the image file
    $sql = "SELECT project_image FROM webdev WHERE id=$project_id";
    $result = mysqli_query($conn, $sql);
    $project = mysqli_fetch_assoc($result);

    if ($project) {
        $project_image = $project['project_image'];
        $image_path = ROOT_PATH . "/uploads/projects/" . $project_image;

        // Delete the project image file if it exists
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Now, delete the project record from the database
        $sql = "DELETE FROM webdev WHERE id=$project_id LIMIT 1";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Project successfully deleted";
            header("Location: manage_webdev_post.php");
            exit(0);
        } else {
            $_SESSION['message'] = "Failed to delete project";
        }
    }
}

?>

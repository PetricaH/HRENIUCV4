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

// function to get all webdev projedts from the webdev table
function getAllWebdevProjects() {
    global $conn;
    $sql = "SELECT w.*, c.name AS category FROM webdev w LEFT JOIN webdev_project_categories c ON w.project_category_id = c.id ORDER BY w.created_at DESC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $webdevPosts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $webdevPosts;
}

// function to handle webdev post creation or editing
if (isset($_POST['save_webdev_post'])) {
    $webdev_id = isset($_POST['webdev_id']) ? $_POST['webdev_id'] : 0;
    $name = esc($_POST['name']);
    $description = esc($_POST['description']);
    $cateogry_id = isset($_POST['category_id']) ? esc($_POST['category_id']) : null;
    $published = isset($_POST['publish']) ? 1 : 0;

    // handle image upload
    if (isset($_FILES['project_image']['name']) && $_FILES['project_image']['name'] != "") {
        $project_image = time() . '_' . $_FILES['project_image']['name'];
        $upload_dir = ROOT_PATH . "/uploads/projects";

        // check if the directory exists, if not, create it 
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true); // creates the directory with write permission
        }

        $target = $upload_dir . $project_image;

        // move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES['project_image']['tmp_name'], $target)) {
            die("Failed to upload image");
        }
    } else {
        // keep  the exisitng image if editing and no new image is uploaded
        if ($isEditingWebDev) {
            $sql = "SELECT project_image FROM webdev WHERE id='$webdev_id' LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $webdev = mysqli_fetch_assoc($result);
            $project_image = $webdev['project_image'];
        }
    }

    if ($isEditingWebDev) {
        // update the webdev record
        $sql = "UPDATE webdev SET name='$name', description='$description', category_id='$cateogry_id', published='$published', project_image='$project_image' WHERE id='$webdev_id'";
        if (!mysqli_query($conn, $sql)) {
            die("Query failed: " . mysqli_error($conn));
        } else {
            $_SESSION['message'] = "Webdev post updated successfully";
            header('Location: manage_webdev_post.php'); // redirect after update (same as for the update)
            exit(0);
        }
    } else {
        // insert new webdev record
        $sql = "INSERT INTO webdev (title, description, project_category_id, published, project_image) VALUES ('$name', '$description', '$cateogry_id', '$published', '$project_image')";
        if (!mysqli_query($conn, $sql)) {
            die("Query failed: " . mysqli_error($conn));
        } else {
            $_SESSION['message'] = "Webdev post created successfully";
            header('Location: manage_webdev_post.php'); // reidrect after creation 
            exit(0);
        }
    }
}

// function to escape form inputs (only declare if it hasn't  been declared yet)
if (!function_exists('esc')) {
    function esc(String $value) {
        global $conn;
        $val = trim($value);
        return mysqli_real_escape_string($conn, $val);
    }
}

// function to delete webdev post
?>


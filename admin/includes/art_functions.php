<?php 

// Initialize variables
$isEditingArt = false;
$art_id = 0;
$name = "";
$description = "";
$category_id = "";
$published = false;
$art_image = "";

// Function to get all artworks from the art table
function getAllArtworks() {
    global $conn;
    $sql = "SELECT a.*, c.name as category FROM art a LEFT JOIN art_categories c ON a.art_category_id = c.id ORDER BY a.created_at DESC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $arts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $arts;
}

// Function to get all categories
function getAllCategories() {
    global $conn;
    $sql = "SELECT * FROM art_categories";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    }

    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $categories;
}

// Function to handle art creation or editing 
if (isset($_POST['save_art'])) {
    $art_id = isset($_POST['art_id']) ? $_POST['art_id'] : 0;
    $name = esc($_POST['name']);
    $description = esc($_POST['description']);
    $category_id = isset($_POST['category_id']) ? esc($_POST['category_id']) : null;
    $published = isset($_POST['publish']) ? 1 : 0;

    // Handle image upload 
    if (isset($_FILES['art_image']['name']) && $_FILES['art_image']['name'] != "") {
        $art_image = time() . '_' . $_FILES['art_image']['name'];
        $upload_dir = ROOT_PATH . "/uploads/art/";

        // Check if the directory exists, if not, create it
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Creates the directory with write permissions
        }

        $target = $upload_dir . $art_image;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES['art_image']['tmp_name'], $target)) {
            die("Failed to upload image");
        }

    } else {
        // Keep the existing image if editing and no new image is uploaded
        if ($isEditingArt) {
            $sql = "SELECT art_image FROM art WHERE id='$art_id' LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $art = mysqli_fetch_assoc($result);
            $art_image = $art['art_image'];
        }
    }

    if ($isEditingArt) {
        // Update the art record
        $sql = "UPDATE art SET name='$name', description='$description', category_id='$category_id', published='$published', art_image='$art_image' WHERE id='$art_id'";
        if (!mysqli_query($conn, $sql)) {
            die("Query failed: " . mysqli_error($conn));
        } else {
            $_SESSION['message'] = "Artwork updated successfully";
            header('Location: manage_art.php'); // redirect after update (needed so that the posts do not get cloned)
            exit(0);
        }
    } else {
        // Insert new art record
        $sql = "INSERT INTO art (name, description, art_category_id, published, art_image) VALUES ('$name', '$description', '$category_id', '$published', '$art_image')";
        if (!mysqli_query($conn, $sql)) {
            die("Query failed: " . mysqli_error($conn));
        } else {
            $_SESSION['message'] = "Artwork created successfully";
            header('Location: manage_art.php'); // redirect after creation (same as for the update)
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

// Function to delete artworks
function deleteArtwork($art_id) {
    global $conn;

    // First, get the artwork to delete the image file
    $sql = "SELECT art_image FROM art WHERE id=$art_id";
    $result = mysqli_query($conn, $sql);
    $artwork = mysqli_fetch_assoc($result);

    if ($artwork) {
        $art_image = $artwork['art_image'];
        $image_path = ROOT_PATH . "/uploads/art/" . $art_image;

        // Delete the artwork image file if it exists
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Now, delete the artwork record from the database
        $sql = "DELETE FROM art WHERE id=$art_id LIMIT 1";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Artwork successfully deleted";
            header("Location: manage_art.php");
            exit(0);
        } else {
            $_SESSION['message'] = "Failed to delete artwork";
        }
    }
}

?>

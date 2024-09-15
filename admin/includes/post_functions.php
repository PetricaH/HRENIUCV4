<?php 

// Initialize variables
$isEditingPost = false;
$post_id = 0;
$title = "";
$body = "";
$topic_id = "";
$published = 0;
$image = "";

// Function to get all posts from the posts table
function getAllPosts() {
    global $conn;
        
        // Admin can view all posts
        // Author can only view their posts
        if ($_SESSION['user']['role'] == "Admin") {
                $sql = "SELECT * FROM posts";
        } elseif ($_SESSION['user']['role'] == "Author") {
                $user_id = $_SESSION['user']['id'];
                $sql = "SELECT * FROM posts WHERE user_id=$user_id";
        }
        $result = mysqli_query($conn, $sql);
        $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $final_posts = array();
        foreach ($posts as $post) {
                $post['author'] = getAdminUsers($post['user_id']);
                array_push($final_posts, $post);
        }
        return $final_posts;
}

// Function to handle post creation or editing 
if (isset($_POST['save_post'])) {
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : 0;
    $title = esc($_POST['title']);
    $body = esc($_POST['body']);
    $topic_id = isset($_POST['topic_id']) ? esc($_POST['topic_id']) : null;
    $published = isset($_POST['publish']) ? 1 : 0;

    // Handle image upload 
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image = time() . '_' . $_FILES['image']['name'];
        $upload_dir = ROOT_PATH . "/uploads/posts/";

        // Check if the directory exists, if not, create it
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Creates the directory with write permissions
        }

        $target = $upload_dir . $image;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            die("Failed to upload image");
        }

    } else {
        // Keep the existing image if editing and no new image is uploaded
        if ($isEditingPost) {
            $sql = "SELECT image FROM posts WHERE id='$post_id' LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $post = mysqli_fetch_assoc($result);
            $image = $post['image'];
        }
    }

    if ($isEditingPost) {
        // Update the post record
        $sql = "UPDATE posts SET title='$title', body='$body', topic_id='$topic_id', published='$published', image='$image' WHERE id='$post_id'";
        if (!mysqli_query($conn, $sql)) {
            die("Query failed: " . mysqli_error($conn));
        } else {
            $_SESSION['message'] = "Post updated successfully";
            header('Location: posts.php'); // redirect after update
            exit(0);
        }
    } else {
        // Insert new post record
        $sql = "INSERT INTO posts (title, body, id, published, image, user_id) VALUES ('$title', '$body', '$topic_id', '$published', '$image', '" . $_SESSION['user']['id'] . "')";
        if (!mysqli_query($conn, $sql)) {
            die("Query failed: " . mysqli_error($conn));
        } else {
            $_SESSION['message'] = "Post created successfully";
            header('Location: posts.php'); // redirect after creation
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

// Function to delete posts
function deletePost($post_id) {
    global $conn;

    // First, get the post to delete the image file
    $sql = "SELECT image FROM posts WHERE id=$post_id";
    $result = mysqli_query($conn, $sql);
    $post = mysqli_fetch_assoc($result);

    if ($post) {
        $image = $post['image'];
        $image_path = ROOT_PATH . "/uploads/posts/" . $image;

        // Delete the post image file if it exists
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Now, delete the post record from the database
        $sql = "DELETE FROM posts WHERE id=$post_id LIMIT 1";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Post successfully deleted";
            header("Location: posts.php");
            exit(0);
        } else {
            $_SESSION['message'] = "Failed to delete post";
        }
    }
}

// action to delete the post (needed so te deletePost function can be executed)

if (isset($_GET['delete-post'])) {
        $post_id = $_GET['delete-post'];
        deletePost($post_id);
}

       // if user clicks the publish post button
       if (isset($_GET['publish']) || isset($_GET['unpublish'])) {
        $message = "";
        if (isset($_GET['publish'])) {
                $message = "Post published successfully";
                $post_id = $_GET['publish'];
        } else if (isset($_GET['unpublish'])) {
                $message = "Post successfully unpublished";
                $post_id = $_GET['unpublish'];
        }
        togglePublishPost($post_id, $message);
    }
    // delete blog post
    function togglePublishPost($post_id, $message)
    {
        global $conn;
        $sql = "UPDATE posts SET published=!published WHERE id=$post_id";
        
        if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = $message;
                header("location: posts.php");
                exit(0);
        }
    }

?>


<!-- 
// Post variables
$post_id = 0;
$isEditingPost = false;
$published = 0;
$title = "";
$post_slug = "";
$body = "";
$image = "";
$post_topic = "";

// if user clicks the create post button
if (isset($_POST['create_post'])) { createPost($_POST); }
// if user clicks the Edit post button
if (isset($_GET['edit-post'])) {
        $isEditingPost = true;
        $post_id = $_GET['edit-post'];
        editPost($post_id);
}
// if user clicks the update post button
if (isset($_POST['update_post'])) {
        updatePost($_POST);
}
// if user clicks the Delete post button
if (isset($_GET['delete-post'])) {
        $post_id = $_GET['delete-post'];
        deletePost($post_id);
}

/* - - - - - - - - - - 
-  Post functions
- - - - - - - - - - -*/
function getAllPosts()
{
        global $conn;
        
        // Admin can view all posts
        // Author can only view their posts
        if ($_SESSION['user']['role'] == "Admin") {
                $sql = "SELECT * FROM posts";
        } elseif ($_SESSION['user']['role'] == "Author") {
                $user_id = $_SESSION['user']['id'];
                $sql = "SELECT * FROM posts WHERE user_id=$user_id";
        }
        $result = mysqli_query($conn, $sql);
        $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $final_posts = array();
        foreach ($posts as $post) {
                $post['author'] = getPostAuthorById($post['user_id']);
                array_push($final_posts, $post);
        }
        return $final_posts;
}
// get the author/username of a post
function getPostAuthorById($user_id)
{
        global $conn;
        $sql = "SELECT username FROM users WHERE id=$user_id";
        $result = mysqli_query($conn, $sql);
        if ($result) {
                // return username
                return mysqli_fetch_assoc($result)['username'];
        } else {
                return null;
        }
}

function createPost($request_values)
        {
                global $conn, $errors, $title, $image, $topic_id, $body, $published;
                $title = esc($request_values['title']);
                $body = htmlentities(esc($request_values['body']));
                if (isset($request_values['topic_id'])) {
                        $topic_id = esc($request_values['topic_id']);
                }
                if (isset($request_values['publish'])) {
                        $published = esc($request_values['publish']);
                }
                // create slug: if title is "The Storm Is Over", return "the-storm-is-over" as slug
                $post_slug = makeSlug($title);
                // validate form
                if (empty($title)) { array_push($errors, "Post title is required"); }
                if (empty($body)) { array_push($errors, "Post body is required"); }
                if (empty($topic_id)) { array_push($errors, "Post topic is required"); }
                // Get image name
                $image = $_FILES['image']['name'];
                if (empty($image)) { array_push($errors, "Featured image is required"); }
                // image file directory
                $target = "../static/images/" . basename($image);
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                        array_push($errors, "Failed to upload image. Please check file settings for your server");
                }
                // Ensure that no post is saved twice. 
                $post_check_query = "SELECT * FROM posts WHERE slug='$post_slug' LIMIT 1";
                $result = mysqli_query($conn, $post_check_query);

                if (mysqli_num_rows($result) > 0) { // if post exists
                        array_push($errors, "A post already exists with that title.");
                }
                // create post if there are no errors in the form
                if (count($errors) == 0) {
                        $query = "INSERT INTO posts (user_id, title, slug, image, body, published, created_at, updated_at) VALUES(1, '$title', '$post_slug', '$image', '$body', $published, now(), now())";
                        if(mysqli_query($conn, $query)){ // if post created successfully
                                $inserted_post_id = mysqli_insert_id($conn);
                                // create relationship between post and topic
                                $sql = "INSERT INTO post_topic (post_id, topic_id) VALUES($inserted_post_id, $topic_id)";
                                mysqli_query($conn, $sql);

                                $_SESSION['message'] = "Post created successfully";
                                header('location: posts.php');
                                exit(0);
                        }
                }
        }

        /* * * * * * * * * * * * * * * * * * * * *
        * - Takes post id as parameter
        * - Fetches the post from database
        * - sets post fields on form for editing
        * * * * * * * * * * * * * * * * * * * * * */
        function editPost($role_id)
        {
                global $conn, $title, $post_slug, $body, $published, $isEditingPost, $post_id;
                $sql = "SELECT * FROM posts WHERE id=$role_id LIMIT 1";
                $result = mysqli_query($conn, $sql);
                $post = mysqli_fetch_assoc($result);
                // set form values on the form to be updated
                $title = $post['title'];
                $body = $post['body'];
                $published = $post['published'];
        }

        function updatePost($request_values)
        {
                global $conn, $errors, $post_id, $title, $image, $topic_id, $body, $published;

                $title = esc($request_values['title']);
                $body = esc($request_values['body']);
                $post_id = esc($request_values['post_id']);
                if (isset($request_values['topic_id'])) {
                        $topic_id = esc($request_values['topic_id']);
                }
                // create slug: if title is "The Storm Is Over", return "the-storm-is-over" as slug
                $post_slug = makeSlug($title);

                if (empty($title)) { array_push($errors, "Post title is required"); }
                if (empty($body)) { array_push($errors, "Post body is required"); }
                // if new featured image has been provided
                if (isset($_POST['image'])) {
                        // Get image name
                        $image = $_FILES['image']['name'];
                        // image file directory
                        $target = "../static/images/" . basename($image);
                        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                                array_push($errors, "Failed to upload image. Please check file settings for your server");
                        }
                }

                // register topic if there are no errors in the form
                if (count($errors) == 0) {
                        $query = "UPDATE posts SET title='$title', slug='$post_slug', views=0, image='$image', body='$body', published=$published, updated_at=now() WHERE id=$post_id";
                        // attach topic to post on post_topic table
                        if(mysqli_query($conn, $query)){ // if post created successfully
                                if (isset($topic_id)) {
                                        $inserted_post_id = mysqli_insert_id($conn);
                                        // create relationship between post and topic
                                        $sql = "INSERT INTO post_topic (post_id, topic_id) VALUES($inserted_post_id, $topic_id)";
                                        mysqli_query($conn, $sql);
                                        $_SESSION['message'] = "Post created successfully";
                                        header('location: posts.php');
                                        exit(0);
                                }
                        }
                        $_SESSION['message'] = "Post updated successfully";
                        header('location: posts.php');
                        exit(0);
                }
        }
        // delete blog post
        function deletePost($post_id)
        {
                global $conn;
                $sql = "DELETE FROM posts WHERE id=$post_id";
                if (mysqli_query($conn, $sql)) {
                        $_SESSION['message'] = "Post successfully deleted";
                        header("location: posts.php");
                        exit(0);
                }
        }

        // if user clicks the publish post button
if (isset($_GET['publish']) || isset($_GET['unpublish'])) {
    $message = "";
    if (isset($_GET['publish'])) {
            $message = "Post published successfully";
            $post_id = $_GET['publish'];
    } else if (isset($_GET['unpublish'])) {
            $message = "Post successfully unpublished";
            $post_id = $_GET['unpublish'];
    }
    togglePublishPost($post_id, $message);
}
// delete blog post
function togglePublishPost($post_id, $message)
{
    global $conn;
    $sql = "UPDATE posts SET published=!published WHERE id=$post_id";
    
    if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = $message;
            header("location: posts.php");
            exit(0);
    }
}
?>  -->
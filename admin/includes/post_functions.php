<?php 
// Post variables
$post_id = 0;
$isEditingPost = false;
$published = 0;
$title = "";
$post_slug = "";
$body = "";
$featured_image = "";
$post_topic = "";

/* - - - - - - - - - - 
-  Post functions
- - - - - - - - - - -*/
// get all posts from DB
function getAllPosts() {
    global $conn; // ensure that $conn is available

    // check if the session variable user and role are set
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role'])) {
        return [];
    }

    // admin can view all posts, author can only view their posts
    if ($_SESSION['user']['role'] == "Admin") {
        $sql = "SELECT * FROM posts";
    } elseif ($_SESSION['user']['role'] == "Author") {
        $user_id = intval($_SESSION['user']['id']); // use intval for security
        $sql = "SELECT * FROM posts WHERE user_id=$user_id";
    } else {
        return[]; // handle roles other than Admin and Author
    }

    // check if $sql is defined and not empty 
    if (empty($sql)) {
        return[]; // 
    }

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        // handle query failure
        return [];
    }

    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $final_posts = [];
    foreach ($posts as $post) {
        $post['author'] = getPostAuthorById($post['user_id']);
        $final_posts[] = $post;
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

/**
 * Creates a new blog post based on the provided form data.
 *
 * @param array $request_values The form data submitted by the user.
 * @param bool $publish Indicates whether the post should be published (true) or saved as a draft (false).
 */
function createPost($request_values, $publish) {
        global $conn, $errors, $title, $featured_image, $topic_id, $body, $published;
    
        // Sanitize and process form inputs
        $title = esc($request_values['title']); // Sanitize title
        $body = htmlentities(esc($request_values['body'])); // Sanitize and convert body text to HTML entities
        if (isset($request_values['topic_id'])) {
            $topic_id = esc($request_values['topic_id']); // Sanitize topic ID if provided
        }
    
        // Set the post's published status based on the publish button click
        $published = $publish ? 1 : 0; // Set to 1 if publish button was clicked, otherwise 0 for draft
    
        // Generate a slug from the post title
        $post_slug = makeSlug($title); // Converts title to a URL-friendly slug
    
        // Get the user ID from the session (should be a valid ID like 12, 13, or 14)
        $user_id = $_SESSION['user']['id'];
    
        // Validate form inputs
        if (empty($title)) {
            array_push($errors, "Post title is required"); // Error if title is missing
        }
        if (empty($body)) {
            array_push($errors, "Post body is required"); // Error if body is missing
        }
        if (empty($topic_id)) {
            array_push($errors, "Post topic is required"); // Error if topic ID is missing
        }
        if (empty($_FILES['featured_image']['name'])) {
            array_push($errors, "Featured image is required"); // Error if image is not uploaded
        }
    
        // Check if a post with the same slug already exists to prevent duplicates
        $post_check_query = "SELECT * FROM posts WHERE slug='$post_slug' LIMIT 1";
        $result = mysqli_query($conn, $post_check_query);
        if (mysqli_num_rows($result) > 0) {
            array_push($errors, "A post already exists with that title"); // Error if post with the same slug is found
        }
    
        // Proceed to create the post if there are no validation errors
        if (count($errors) == 0) {
            $featured_image = $_FILES['featured_image']['name']; // Get the name of the uploaded image
            $target = "../static/images/" . basename($featured_image); // Define the target path for the uploaded image
    
            // Attempt to move the uploaded image to the target directory
            if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
                array_push($errors, "Failed to upload image. Please check file settings for your server"); // Error if image upload fails
            } else {
                // Insert the new post into the database
                $query = "INSERT INTO posts (user_id, title, slug, image, body, published, created_at, updated_at) 
                          VALUES($user_id, '$title', '$post_slug', '$featured_image', '$body', $published, now(), now())";
                if (mysqli_query($conn, $query)) {
                    // Retrieve the ID of the newly inserted post
                    $inserted_post_id = mysqli_insert_id($conn);
    
                    // Link the new post with its topic
                    $sql = "INSERT INTO post_topic (post_id, topic_id) VALUES($inserted_post_id, $topic_id)";
                    mysqli_query($conn, $sql);
    
                    // Set a success message and redirect to the posts page
                    $_SESSION['message'] = "Post created successfully";
                    header('location: posts.php');
                    exit(0);
                }
            }
        }
    }
    
function editPost($role_id) {
        global $conn, $title, $post_slug, $body, $published, $isEditingPost, $post_id;
        $sql = "SELECT * FROM posts WHERE id=$role_id LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $post = mysqli_fetch_assoc($result);
        // set form values on the form to be updated 
        $title = $post['title'];
        $body = $post['body'];
        $published = $post['published'];
}

function updatePost($request_values) {
        global $conn, $errors, $post_id, $title, $featured_image, $topic_id, $body, $published;
        $title = esc($request_values['title']);
        $body = esc($request_values['body']);
        $post_id = esc($request_values['post_id']);
        if (isset($request_values['topic_id'])) {
            $topic_id = esc($request_values['topic_id']);
        }
    
        // Set $published based on the checkbox value
        $published = isset($request_values['publish']) ? 1 : 0;
    
        // create slug
        $post_slug = makeSlug($title);
    
        if (empty($title)) { array_push($errors, "Post title is required."); }
        if (empty($body)) { array_push($errors, "Post body is required"); }
    
        if (isset($_FILES['featured_image']['name']) && !empty($_FILES['featured_image']['name'])) {
            $featured_image = $_FILES['featured_image']['name'];
            $target = "../static/images/" . basename($featured_image);
            if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
                array_push($errors, "Failed to upload image. Please check file settings for your server");
            }
        }
    
        if (count($errors) == 0) {
            $query = "UPDATE posts SET title='$title', slug='$post_slug', image='$featured_image', body='$body', 
                      published=$published, updated_at=now() WHERE id=$post_id";
    
            if (mysqli_query($conn, $query)) {
                if (isset($topic_id)) {
                    $sql = "INSERT INTO post_topic (post_id, topic_id) VALUES($post_id, $topic_id)";
                    mysqli_query($conn, $sql);
                }
                $_SESSION['message'] = "Post updated successfully";
                header('location: posts.php');
                exit(0);
            }
        }
    }
    

// delete blog post
function deletePost($post_id) {
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

//delete blog post
function togglePublishPost($post_id, $message) {
        global $conn;
        $sql = "UPDATE posts SET published=!published WHERE id=$post_id";

        if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = $message;
                header("location: posts.php");
                exit(0);
        }
}

// POST ACTIONS FROM HERE DOWNWARDS

// if user clicks the create post button
if (isset($_POST['create_post'])) {createPost($_POST); }
//if user clicks the edit post button
if (isset($_GET['edit-post'])) {
        $isEditingPost = true;
        $post_id = $_GET['edit-post'];
        editPost($post_id);
}
// if user clicks the update post button 
if (isset($_POST['update-post'])) {
        updatePost($_POST);
}
// if user clicks the delete post button
if (isset($_GET['delete-post'])) {
        $post_id = $_GET['delete-post'];
        deletePost($post_id);
}

if (isset($_POST['save_post'])) {
        // Handle saving the post as a draft
        createPost($_POST, false);
    } elseif (isset($_POST['publish_post'])) {
        // Handle publishing the post
        createPost($_POST, true);
    }
?>
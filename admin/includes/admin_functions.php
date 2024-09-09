<?php 

// admin user variables

use function PHPSTORM_META\type;

$admin_id = 0;
$isEditingUser = false;
$username = "";
$role = "";
$email = "";
//general variables 
$errors = [];

// art category variables

$category_id = 0;
$isEditingCategory = false;
$category_name = "";

//topic variables

$topic_id = 0;
$isEditingTopic = false;
$topic_name = "";

// Initialize variables
$category_id = 0;
$isEditingCategory = false;
$category_name = "";

// admin users actions

//if user clicks the create admin button
if (isset($_POST['create_admin'])) {
    createAdmin($_POST);
}

//  if user clicks the edit admin button 
if (isset($_GET['edit-admin'])) {
    $isEditingUser = true;
    $admin_id = $_GET['edit-admin'];
    editAdmin($admin_id);
}

// if user clicks the update admin button

if (isset($_POST['update_admin'])) {
    updateAdmin($_POST);
}

//if user clicks the delete admin button
if (isset($_GET['delete-admin'])) {
    $admin_id = $_GET['delete-admin'];
    deleteAdmin($admin_id);
}

//TOPIC ACTIONS FROM HERE DOWNWARDS

//if user clicks the create topic button
if (isset($_POST['create_topic'])) { createTopic($_POST); }

//if user clicks the edit topic button

if (isset($_GET['edit-topic'])) {
    $isEditingTopic = true;
    $topic_id = $_GET['edit-topic'];
    editTopic($topic_id);
}

//if user clicks the update topic button
if (isset($_POST['update_topic'])) {
    updateTopic($_POST);
}

//if user clicks the delete topic button
if (isset($_GET['delete-topic'])) {
    $topic_id = $_GET['delete-topic'];
    deleteTopic($topic_id);
}

//ART CATEGORY ACTIONS FROM HERE DOWNWARDS

// Create art category
if (isset($_POST['create_category'])) { 
    createArtCategory($_POST); 
}

// Edit art category
if (isset($_GET['edit-art-category'])) {
    $isEditingCategory = true;
    $category_id = $_GET['edit-art-category'];
    editArtCategory($category_id);
}

// Update art category
if (isset($_POST['update_category'])) {
    updateArtCategory($_POST);
}

// Delete art category
if (isset($_GET['delete-art-category'])) {
    $category_id = $_GET['delete-art-category'];
    deleteArtCategory($category_id);
}

//WEB DEV ACTIONS FROM HERE DOWNWARDS

// Create web development category
if (isset($_POST['create_webdev_category'])) { 
    createWebDevCategory($_POST); 
}

// Edit web development category
if (isset($_GET['edit-webdev-category'])) {
    $isEditingWebDevCategory = true;
    $webdev_category_id = $_GET['edit-webdev-category'];
    editWebDevCategory($webdev_category_id);
}

// Update web development category
if (isset($_POST['update_webdev_category'])) {
    updateWebDevCategory($_POST);
}

// Delete web development category
if (isset($_GET['delete-webdev-category'])) {
    $webdev_category_id = $_GET['delete-webdev-category'];
    deleteWebDevCategory($webdev_category_id);
}

//ADMIN USERS FUNCTIONS FROM HERE DOWNWARDS

function createAdmin($request_values){
    global $conn, $errors, $role, $username, $email;
    $username = esc($request_values['username']);
    $email = esc($request_values['email']);
    $password = esc($request_values['password']);
    $passwordConfirmation = esc($request_values['passwordConfirmation']); 

    if(isset($request_values['role'])){
        $role = esc($request_values['role']);
    }
    //form validation: ensure that the form is correctly filled
    if (empty($username)) {array_push($errors, "Uhm, not good"); }
    if (empty($email)) {array_push($errors, "Uhm the emails is not good"); }
    if (empty($role)) {array_push($errors, "role is required for admin users"); }
    if (empty($password)) {array_push($errors, "uhm the password is not good"); }
    if ($password != $passwordConfirmation) { array_push($errors, "The two passwords do not match"); }
    //ensure that no user is registered twice.
    //the email and usernames should be unique
    $user_check_query = "SELECT * FROM users WHERE username='$username'
                                                    OR email='$email' LIMIT 1";
    $result = mysqli_query($conn, $user_check_query);
    $user = mysqli_fetch_assoc($result);
    if ($user) { //if user exists
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists.");
        }
        if ($user['email'] === $email) {
            array_push($errors, "Email already exists");
        } 
    }

    //register user if therre are no errors in the form
    if (count($errors) == 0) {
        $password = md5($password); //encript the password before saving in the database
        $query = "INSERT INTO users (username, email, role, password,  created_at, updated_at)
                    VALUES('$username', '$email', '$role', '$password', now(), now())";
        mysqli_query($conn, $query);

        $_SESSION['message'] = "admin user created succesfully";
        header('location: users.php');
        exit(0);
    }
}

//delete admin user
function deleteAdmin($admin_id) {
    global $conn;
    $sql = "DELETE FROM users WHERE id=$admin_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "user succsessfully deleted";
        header("location: users.php");
        exit(0);
    }
}

function editAdmin($admin_id){
    global $conn, $username, $role, $isEditingUser, $admin_id, $email;

    $sql = "SELECT * FROM users WHERE id=$admin_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($result);

    //set form values ($username and $email) on the form to be updated
    $username = $admin['username'];
    $email = $admin['email'];
}

//  receives  admin requestss from form and updates in database
function updateAdmin($request_values){
    global $conn, $errors, $role, $username, $isEditingUser, $admin_id, $email;
    // get id of the admin to be updated
    $admin_id = $request_values['admin_id'];
    // set edit state to false
    $isEditingUser = false;
}
// returns all the admin users and their corresponding roles
function getAdminUsers() {
    global $conn, $roles;
    $sql = "SELECT * FROM users WHERE role IS NOT NULL";
    $result = mysqli_query($conn, $sql);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $users;
}

// escape from submitted value, hence, preventing SQL injection

function esc(String $value){
    //bring the global db connect object into function
    global $conn;
    // remove the empty space surrounding the string
    $val = trim($value);
    $val = mysqli_real_escape_string($conn, $value);
    return $val;
}

// receives a string like 'Some Sample String'
// and returns 'some-sample-string'
function makeSlug(String $string){
    $string = strtolower($string);
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    return $slug;
}

//TOPIC FUNCTIONS FROM HERE DOWNWARDS

//get all topics from DB
function getAllTopics() {
    global $conn;
    $sql = "SELECT * FROM topics";
    $result = mysqli_query($conn, $sql);
    $topics = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $topics;
}

function createTopic($request_values){
    global $conn, $errors, $topic_name;
    $topic_name = esc($request_values['topic_name']);
    // create slug: if topic is "life advie", return "life-advice as slug 
    $topic_slug = makeSlug($topic_name);
    // validate form
    if (empty($topic_name)) {
        array_push($errors, "topic name required");
    }
    // ensure that no topic is saved twice.
    $topic_check_query = "SELECT * FROM topics WHERE slug='$topic_slug' LIMIT 1";
    $result = mysqli_query($conn, $topic_check_query);
    if (mysqli_num_rows($result) > 0) { // if topic exists
        array_push($errors, "topic already exists");
    }
    // register topic if there are no errors in the form
    if (count($errors) == 0) {
        $query = "INSERT INTO topics (name, slug)
                            VALUE('$topic_name', '$topic_slug')";
        mysqli_query($conn, $query);

        $_SESSION['message'] = "topic created successfully";
        header('location: topics.php');
        exit(0);
    }
}

/* - takes topic id as parameter 
   - fetches the topic from database
   - sets topic fields on form for editing
*/
function editTopic($topic_id) {
    global $conn, $topic_name, $isEditingTopic, $topic_id;
    $sql = "SELECT * FROM topics WHERE id=$topic_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $topic = mysqli_fetch_assoc($result);
    // set form values ($topic_name) on the form to be updated 
    $topic_name = $topic['name'];
}

function updateTopic($request_values) {
    global $conn, $errors, $topic_name, $topic_id;
    $topic_name = esc($request_values['topic_name']);
    $topic_id = esc($request_values['topic_id']);
    // create sulg: if topic is "life-advice" as slug
    $topic_slug = makeSlug($topic_name);
    // validate form
    if (empty($topic_name)) {
        array_push($errors, "Topic name required");
    }
    // register topic if there are no errors in the form
    if (count($errors) === 0) {
        $query ="UPDATE topics SET name='$topic_name', slug='$topic_slug' WHERE id=$topic_id";
        mysqli_query($conn, $query);

        $_SESSION['message'] = "topic update successfully";
        header('location: topics.php');
        exit(0);
    }
}

// delete topic
function deleteTopic($topic_id) {
    global $conn;
    $sql = "DELETE FROM topics WHERE id=$topic_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "topic successfully delete";
        header("location: topics.php");
        exit(0);
    }
}


//ART CATEGORIES FUNCTIONS FROM HERE DOWNWARDS

//get all art categories from database
function getAllArtCategories() {
    global $conn;
    $sql = "SELECT * FROM art_categories";
    $result = mysqli_query($conn, $sql);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $categories;
}

//create art categories
function createArtCategory($request_values) {
    global $conn, $errors, $category_name;
    $category_name = esc($request_values['category_name']);
    // create  slug: if category is "live advice", return "life-advice" as slug
    $category_slug = makeSlug($category_name);
    // validate form
    if (empty($category_name)) {
        array_push($errors, "category name required");
    }
    //ensure that no category is saved twice
    $category_check_query = "SELECT * FROM art_categories WHERE slug='$category_slug' LIMIT 1";
    $result = mysqli_query($conn, $category_check_query);
    if (mysqli_num_rows($result) > 0) { // if category exists
        array_push($errors, "art category already exists");    
    }
    // register art category if there are no errors in the form
    if (count($errors) == 0) {
        $query = "INSERT INTO art_categories (name, slug)
                            VALUE('$category_name', '$category_slug')";
        mysqli_query($conn, $query);

        $_SESSION['message'] = "art category created successfully";
        header('location: art_categories.php');
        exit(0);
    }
}

/*
- takes category id as parameter
- fetches the category from the dataase
- sets category fiels on form for editing
*/
function editArtCategory($category_id) {
    global $conn, $category_name, $isEditingCategory, $category_id;
    $sql = "SELECT * FROM categories WHERE id=$category_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $category = mysqli_fetch_assoc($result);
    //set the form values ($category_name) on the form to be updated
    $category_name = $category['title'];
}
function updateArtCategory($request_values) {
    global $conn, $errors, $category_name, $category_id;
    $category_name = esc($request_values['category_name']);
    $category_id = esc($request_values['category_id']);
    // create slug: if category is "life advice" return "life-advice" as slug
    $category_slug = makeSlug($category_name);
    // validate form 
    if (empty($category_name)) {
        array_push($errors, "Category name is required");
    }
    // register categoryif there are no errors in the form
    if (count($errors) === 0) {
        $query = "UPDATE categories SET title='$category_name', slug='$category_slug' WHERE id=$category_id";
        mysqli_query($conn, $query);

        $_SESSION['message'] = "category update successfully";
        header('location: art_categories.php');
        exit(0);
    }
}

// delete art category 
function deleteArtCategory($category_id) {
    global $conn;
    $sql = "DELETE FROM art_categories WHERE id=$category_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "art category successfully deleted";
        header('location: art_categories.php');
    }
}

// FUNCTIONS FROM HERE DOWNWARDS FOR WEB DEV

//WEB DEVELOPMENT CATEGORIES FUNCTIONS FROM HERE DOWNWARDS

// function to get all categories for webdev projects
function getAllWebdevCategories() {
    global $conn;
    $sql = "SELECT * FROM webdev_project_categories";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $webdevPostsCategories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $webdevPostsCategories;
}

// Create web development category
function createWebDevCategory($request_values) {
    global $conn, $errors, $webdev_category_name, $webdev_category_slug;
    $webdev_category_name = esc($request_values['webdev_category_name']);
    
    // create slug if empty
    if (empty($webdev_category_slug)) {
        $webdev_category_slug = makeSlug($webdev_category_name);
    }
    
    // Validate form
    if (empty($webdev_category_name)) {
        array_push($errors, "Category name required");
    }
    
    // Ensure no category is saved twice
    $category_check_query = "SELECT * FROM webdev_project_categories WHERE slug='$webdev_category_slug' LIMIT 1";
    $result = mysqli_query($conn, $category_check_query);
    if (mysqli_num_rows($result) > 0) { // if category exists
        array_push($errors, "Web development category already exists");
    }
    
    // Register web development category if there are no errors
    if (count($errors) == 0) {
        $query = "INSERT INTO webdev_project_categories (name, slug)
                  VALUES('$webdev_category_name', '$webdev_category_slug')";
        mysqli_query($conn, $query);

        $_SESSION['message'] = "Web development category created successfully";
        header('location: webdev_categories.php');
        exit(0);
    }
}

// Edit web development category
function editWebDevCategory($webdev_category_id) {
    global $conn, $webdev_category_name, $webdev_category_slug, $isEditingWebDevCategory, $webdev_category_id;
    $sql = "SELECT * FROM webdev_project_categories WHERE id=$webdev_category_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $category = mysqli_fetch_assoc($result);
    
    // Set form values for editing
    $webdev_category_name = $category['name'];
    $webdev_category_slug = $category['slug'];
}

// Update web development category
function updateWebDevCategory($request_values) {
    global $conn, $errors, $webdev_category_name, $webdev_category_slug, $webdev_category_id;
    $webdev_category_name = esc($request_values['webdev_category_name']);
    $webdev_category_slug = esc($request_values['webdev_category_slug']);
    $webdev_category_id = esc($request_values['webdev_category_id']);
    
    // Create slug if empty
    if (empty($webdev_category_slug)) {
        $webdev_category_slug = makeSlug($webdev_category_name);
    }
    
    // Validate form
    if (empty($webdev_category_name)) {
        array_push($errors, "Category name is required");
    }
    
    // Update category if there are no errors
    if (count($errors) === 0) {
        $query = "UPDATE webdev_project_categories SET name='$webdev_category_name', slug='$webdev_category_slug' WHERE id=$webdev_category_id";
        mysqli_query($conn, $query);

        $_SESSION['message'] = "Web development category updated successfully";
        header('location: webdev_categories.php');
        exit(0);
    }
}

// Delete web development category
function deleteWebDevCategory($webdev_category_id) {
    global $conn;
    $sql = "DELETE FROM webdev_project_categories WHERE id=$webdev_category_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Web development category successfully deleted";
        header('location: webdev_categories.php');
    }
}


// END OF FUNCTIONS FOR WEB DEV

?>
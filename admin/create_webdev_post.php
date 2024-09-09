<?php 
include('../config.php'); 
include(ROOT_PATH . '/admin/includes/admin_functions.php'); 
include(ROOT_PATH . '/admin/includes/webdev_functions.php'); 
include(ROOT_PATH . '/admin/includes/head_section.php'); 

// Initialize the variable
$isEditingWebDevPost = false;

// Check if the user is editing an existing project
if (isset($_GET['edit'])) {
    $webdev_post_id = $_GET['edit'];
    $isEditingWebDevPost = true;

    // Fetch the existing project data for editing
    $sql = "SELECT * FROM webdev WHERE id=$webdev_post_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $webdev_post = mysqli_fetch_assoc($result);

    // Set the form fields with existing data
    $title = $webdev_post['title'];
    $description = $webdev_post['description'];
    $category_id = $webdev_post['project_category_id'];
    $published = $webdev_post['published'];
    $project_image = $webdev_post['project_image'];
} else {
    // If creating a new project, initialize default values
    $title = '';
    $description = '';
    $category_id = '';
    $published = false;
    $project_image = '';
}

// Get all web development categories
$webdevCategories = getAllWebDevCategories(); 
?>

<title>Admin | Create Web Development Project</title>
</head>
<body>
    <!-- Admin navbar -->
    <?php include(ROOT_PATH . '/admin/includes/navbar.php'); ?>
    <div class="container content">
        <!-- Left side menu -->
        <?php include(ROOT_PATH . '/admin/includes/menu.php'); ?>
        
        <!-- Middle form - to create and edit web development project -->
        <div class="action create-webdev-div">
            <h1 class="page-title">Create/Edit Web Development Project</h1>
            <form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL . 'admin/create_webdev_post.php'; ?>" >
                <!-- Validation errors for the form -->
                <?php include(ROOT_PATH . '/includes/errors.php') ?>

                <!-- If editing webdev project, the id is required to identify that project -->
                <?php if ($isEditingWebDevPost === true): ?>
                    <input type="hidden" name="webdev_post_id" value="<?php echo $webdev_post_id; ?>" >
                <?php endif ?>
                
                <input type="text" name="title" value="<?php echo $title; ?>" placeholder="Title of the Project">
                <label style="float: left; margin: 5px auto 5px;">Upload Project Image</label>
                <input type="file" name="project_image">
                <textarea name="description" id="description" cols="30" rows="10" placeholder="Description of the project"><?php echo $description; ?></textarea>
                <select name="category_id">
                    <option value="" selected disabled>Choose Category</option>
                    <?php foreach ($webdevCategories as $webdevCategory): ?>
                        <option value="<?php echo $webdevCategory['id']; ?>" <?php if($category_id == $webdevCategory['id']) echo 'selected'; ?>>
                            <?php echo $webdevCategory['name']; ?>
                        </option>
                    <?php endforeach ?>
                </select>

                <!-- Only admin users can view publish input field -->
                <?php if ($_SESSION['user']['role'] == "Admin"): ?>
                    <!-- Display checkbox according to whether the project has been published or not -->
                    <?php if ($published == true): ?>
                        <label for="publish">
                            Publish
                            <input type="checkbox" value="1" name="publish" checked="checked">&nbsp;
                        </label>
                    <?php else: ?>
                        <label for="publish">
                            Publish
                            <input type="checkbox" value="1" name="publish">&nbsp;
                        </label>
                    <?php endif ?>
                <?php endif ?>

                <!-- Submit button -->
                <button type="submit" class="btn" name="save_webdev_post">Save Project</button>
            </form>
        </div>
        <!-- End of middle form to create and edit -->
    </div>
</body>
</html>

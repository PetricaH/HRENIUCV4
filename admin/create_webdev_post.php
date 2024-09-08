<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/webdev_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>

<?php $categories = getAllWebdevCategories(); ?>

<title>Admin | Create WebDev Post</title>
</head>
<body>
    <!-- admin navbar -->
    <?php include(ROOT_PATH . '/admin/includes/navbar.php'); ?>
    <div class="container content">
        <!-- left side menu -->
        <?php include(ROOT_PATH . '/admin/includes/menu.php'); ?>
        <!-- middle form - to create and edit webdev post -->
        <div class="action create_webdev_post_div">
            <h1 class="page-title">Create/Edit Webdev Post</h1>
            <form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL . '/admin/create_webdev_post.php'; ?> ">
                <!-- validation errors for the form -->
                <?php include(ROOT_PATH . '/includes/errors.php'); ?>
                <!-- // if edditing webdev project, the id is required to identifiy that project -->
                <?php if ($isEditingWebdevProject === true): ?>
                    <input type="hidden" name="webdev_project_id" value="<?php echo $webdev_project_id; ?>" >
                <?php endif ?>
                    
                    <input type="text" name="name" value="<?php echo $name; ?>" placeholder="Name of the Webdev Project">
                    <label style="float: left; margin: 5px auto 5px;">Upload Webdev Project</label>
                    <input type="file" name="webdev_project_image">
                    <textarea name="descrption" id="description" cols="30" rows="10" placeholder="Description of the Webdev Project"><?php echo $description; ?></textarea>
                    <select name="category_id">
                        <option value="" selected disabled>Choose Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>">
                                <?php echo $category['name']; ?>
                            </option>
                        <?php endforeach ?>
                    </select>

                    <!-- only admin users can view publish input field -->
                    <?php if ($_SESSION['user']['role'] == "Admin"): ?>
                        <!-- display checkbox according to whether the webdev project has been published or not -->
                        <?php if ($published == true): ?>
                            <label for="publihs">
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
                <button type="submit" class="btn" name="save_art">Save Art</button>
            </form>
        </div>
    </div>
</body>
</html>
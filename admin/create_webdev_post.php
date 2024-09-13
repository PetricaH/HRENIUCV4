<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/webdev_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>

<!-- get all the web dev categories for the dropdown (similar to arts and topics) -->
<?php $webdevPostsCategories = getAllWebdevCategories(); ?>

<title>Admin | Create WebDev Post</title>
</head>
<body>
    <!-- admin navbar -->
    <?php include(ROOT_PATH . '/admin/includes/navbar.php'); ?>
    <div class="container content">
        <!-- left side menu -->
        <?php include(ROOT_PATH . '/admin/includes/menu.php'); ?>

        <!-- middle form - to create and edit webdev posts -->
        <div class="action create-webdevPost-div">
            <h1 class="page-title">Create/Edit Webdev Post</h1>
            <form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL . 'admin/create_webdev_post.php'; ?>">
                <!-- validation errors for the form -->
                <?php include(ROOT_PATH . '/includes/errors.php'); ?>
                <!-- if editing webdev post, the id is required to identify that webdev post -->
                <?php if ($isEditingWebDev === true): ?>
                    <input type="hidden" name="webdev_post_id" value="<?php echo $webdev_post_id; ?>">
                <?php endif ?>

                <input type="text" name="name" value="<?php echo $title; ?>" placeholder="Name of the Webdev Post">
                <label style="float: left; margin: 5px auto 5px;">Featured Image</label>
                <input type="file" name="webdev_project_image">
                <textarea name="description" id="description" cols="30" rows="10" placeholder="Description of Webdev Project"><?php echo $description; ?></textarea>
                <select name="webdev_project_category_id">
                    <option value="" selected disabled>Choose Category</option>
                    <?php foreach ($webdevPostsCategories as $webdevPostsCategory): ?>
                        <option value="<?php echo $webdevPostsCategory['id']; ?>">
                            <?php echo $webdevPostsCategory['name']; ?>
                        </option>
                    <?php endforeach ?>
                </select>

                <!-- Only admin users can view publish input field -->
                <?php if ($_SESSION['user']['role'] == "Admin"): ?>
                    <!-- Display checkbox according to whether the artwork has been published or not -->
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
                <button type="submit" class="btn" name="save_webdev_post">Save as Draft</button>
                <button type="submit" class="btn" name="publish_webdev_post">Publish</button>
            </form>
        </div>
    </div>
</body>
</html>
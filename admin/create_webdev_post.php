<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/webdev_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>

<!-- get all the web dev categories (similar to arts and topics) -->
<?php $webdevProjectCategories = getAllWebdevCategories(); ?>
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
            <form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL . 'admin/create_webdev_pots.php'; ?>">
                <!-- validation errors for the form -->
                <?php include(ROOT_PATH . '/includes/errors.php'); ?>
                <!-- if editing webdev post, the id is required to identify that webdev post -->
                <?php if ($isEditingWebDev === true): ?>
                    <input type="hidden" name="webdev_post_id" value="<?php echo $webdev_post_id; ?>">
                <?php endif ?>

                <input type="text" name="name" value="<?php echo $title; ?>" placeholder="Name of the Webdev Post">
                <label style="float: left; margin: 5px auto 5px;">Upload Webdev Image</label>
                <input type="file" name="webdev_project_image">
                <textarea name="description" id="description" cols="30" rows="10" placeholder="Description of Webdev Project"><?php echo $description; ?></textarea>
                <select name="webdev_project_category_id">
                    <option value="" selected disabled>Choose Category</option>
                    <?php foreach ($webdev_project_categories as $webdev_project_category): ?>
                        <option value="<?php echo $webdev_project_category['id']; ?>">

                        </option>
                    <?php endforeach ?>
                </select>
            </form>
        </div>
    </div>
</body>
</html>
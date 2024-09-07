<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/art_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>

<!-- Get all art categories (similar to topics) -->
<?php $categories = getAllCategories(); ?>

<title>Admin | Create Art</title>
</head>
<body>
    <!-- Admin navbar -->
    <?php include(ROOT_PATH . '/admin/includes/navbar.php'); ?>
    <div class="container content">
        <!-- Left side menu -->
        <?php include(ROOT_PATH . '/admin/includes/menu.php'); ?>
        
        <!-- Middle form - to create and edit art -->
        <div class="action create-art-div">
            <h1 class="page-title">Create/Edit Digital Art</h1>
            <form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL . 'admin/create_art.php'; ?>" >
                <!-- Validation errors for the form -->
                <?php include(ROOT_PATH . '/includes/errors.php') ?>

                <!-- If editing art, the id is required to identify that artwork -->
                <?php if ($isEditingArt === true): ?>
                    <input type="hidden" name="art_id" value="<?php echo $art_id; ?>" >
                <?php endif ?>
                
                <input type="text" name="name" value="<?php echo $name; ?>" placeholder="Name of the Artwork">
                <label style="float: left; margin: 5px auto 5px;">Upload Artwork</label>
                <input type="file" name="art_image">
                <textarea name="description" id="description" cols="30" rows="10" placeholder="Description of the artwork"><?php echo $description; ?></textarea>
                <select name="category_id">
                    <option value="" selected disabled>Choose Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo $category['name']; ?>
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
                <button type="submit" class="btn" name="save_art">Save Art</button>
            </form>
        </div>
        <!-- End of middle form to create and edit -->
    </div>
</body>
</html>
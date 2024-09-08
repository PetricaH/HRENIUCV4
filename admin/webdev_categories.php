<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>

<!-- Initialize variables -->
<?php
$isEditingWebDevCategory = false;
$webdev_category_name = '';
$webdev_category_slug = '';
?>

<!-- get all web development categories from the database -->
<?php $webdevCategories = getAllWebDevCategories(); ?>
<title>Admin | Manage Web Development Categories</title>
</head>
<body>
    <!-- admin navbar -->
    <?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>
    <div class="container content">
        <!-- left side menu -->
        <?php include(ROOT_PATH . '/admin/includes/menu.php') ?>

        <!-- middle form - to create and edit -->
        <div class="action">
            <h1 class="page-title">Create/Edit Web Development Categories</h1>
            <form method="post" action="<?php echo BASE_URL . 'admin/webdev_categories.php'; ?>">
                <!-- validation errors for the form -->
                <?php include(ROOT_PATH . '/includes/errors.php') ?>
                <!-- if editing web development category, the id is required to identify that category -->
                <?php if ($isEditingWebDevCategory === true): ?>
                    <input type="hidden" name="webdev_category_id" value="<?php echo $webdev_category_id; ?>">
                <?php endif ?>
                <input type="text" name="webdev_category_name" value="<?php echo $webdev_category_name; ?>" placeholder="Category">
                <input type="text" name="webdev_category_slug" value="<?php echo $webdev_category_slug; ?>" placeholder="Slug">
                <!-- if editing category, display the update button instead of create button -->
                <?php if ($isEditingWebDevCategory === true): ?>
                    <button type="submit" class="btn" name="update_webdev_category">UPDATE</button>
                <?php else: ?>
                    <button type="submit" class="btn" name="create_webdev_category">Save Category</button>
                <?php endif ?>
            </form>
        </div>

        <!-- display records from database -->
        <div class="table-div">
            <!-- display notification message -->
            <?php include(ROOT_PATH . '/includes/messages.php') ?>
            <?php if (empty($webdevCategories)): ?>
                <h1>No categories in the database.</h1>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <th>N</th>
                        <th>Category Name</th>
                        <th>Slug</th>
                        <th colspan="2">Action</th>
                    </thead>
                    <tbody>
                        <?php foreach ($webdevCategories as $key => $webdevCategory): ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo $webdevCategory['name']; ?></td>
                                <td><?php echo $webdevCategory['slug']; ?></td>
                                <td>
                                    <a class="fa fa-pencil btn edit"
                                        href="webdev_categories.php?edit-webdev-category=<?php echo $webdevCategory['id'] ?>">
                                    </a>
                                </td>
                                <td>
                                    <a class="fa fa-trash btn delete"
                                        href="webdev_categories.php?delete-webdev-category=<?php echo $webdevCategory['id'] ?>">
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php endif ?>
        </div>
    </div>
</body>
</html>

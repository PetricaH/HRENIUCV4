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
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="title">Project Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Project Description:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="technologies">Technologies Used (comma separated):</label>
            <input type="text" name="technologies" id="technologies" required>

            <label for="project_image">Upload Project Image:</label>
            <input type="file" name="project_image" id="project_image" required>

            <button type="submit">Add Project</button>
        </form>
    </div>
</body>
</html>
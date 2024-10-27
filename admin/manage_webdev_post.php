<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/webdev_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
<?php $webdevprojects = getPublishedWebdevProjects(); ?>
<title>Admin | Manage Web Development Projects</title>
</head>
<body>
<!-- Admin navbar -->
<?php include(ROOT_PATH . '/admin/includes/navbar.php'); ?>
<div class="container content">
    <!-- Left side menu -->
    <?php include(ROOT_PATH . '/admin/includes/menu.php'); ?>

    <!-- Display records from the webdev table -->
    <div class="table-div" style="width: 80%;">
        <!-- Display notification message -->
        <?php include(ROOT_PATH . '/includes/messages.php'); ?>
        <?php $webdevprojects = getPublishedWebdevProjects(); ?>

        <?php if (empty($webdevprojects)): ?>
            <h1>No web development projects found</h1>
        <?php else: ?>
            <table class="table">
                <thead>
                    <th>No</th>
                    <th>Project Title</th>
                    <th>Description</th>
                    <th>Published</th>
                    <th>Image</th>
                    <th colspan="3">Action</th>
                </thead>
                <tbody>
                    <?php foreach ($webdevprojects as $key => $webdevproject): ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo $webdevproject['title']; ?></td>
                            <td><?php echo $webdevproject['description']; ?></td>
                            <td><?php echo $webdevproject['published'] ? "Yes" : "No"; ?></td>
                            <td><img src="<?php echo BASE_URL . '/uploads/projects/' . $webdevproject['project_image']; ?>" alt="" style="height: 60px;"></td>
                            <td>
                                <a class="fa fa-pencil btn edit" href="create_webdev_post.php?edit-project=<?php echo $webdevproject['id'] ?>"></a>
                            </td>
                            <td>
                                <a class="fa fa-trash btn delete" href="manage_webdev_post.php?delete-project=<?php echo $webdevproject['id']; ?>"></a>
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

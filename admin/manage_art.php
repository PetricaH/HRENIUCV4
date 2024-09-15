<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/art_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>

<title>Admin | Manage Art</title>
</head>
<body>
<?php 

// Check if the delete request has been made
if (isset($_GET['delete-art'])) {
    $art_id = $_GET['delete-art'];
    deleteArtwork($art_id);
}
?>

    <!-- Admin navbar -->
    <?php include(ROOT_PATH . '/admin/includes/navbar.php'); ?>
    <div class="container content">
        <!-- Left side menu -->
        <?php include(ROOT_PATH . '/admin/includes/menu.php'); ?>

        <!-- Display records from the art table -->
        <div class="table-div" style="width: 80%;">
            <!-- Display notification message -->
            <?php include(ROOT_PATH . '/includes/messages.php'); ?>
            <?php $arts = getAllArtworks(); ?>

            <?php if (empty($arts)): ?>
                <h1>No art posts found</h1>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <th>No</th>
                        <th>Author</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Published</th>
                        <th>Image</th>
                        <!-- Only Admin can publish/unpublish post -->
                        <?php if ($_SESSION['user']['role'] == "Admin"): ?>
                            <th><small>Publish</small></th>
                        <?php endif ?>
                        <th><small>Edit</small></th>
                        <th><small>Delete</small></th>
                    </thead>
                    <tbody>
                        <?php foreach ($arts as $key => $art): ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo $art['username']; ?></td>
                                <td><?php echo $art['title']; ?></td>
                                <td><?php echo $art['art_category_id']; ?></td>
                                <td><?php echo $art['published'] ? "Yes" : "No"; ?></td>
                                <td><img src="<?php echo BASE_URL . '/uploads/art/' . $art['art_image']; ?>" alt="" style="height: 60px;"></td>
                                <!-- Only Admin can publish/unpublish post -->
                                <?php if ($_SESSION['user']['role'] == "Admin" ): ?>
                                                                <td>
                                                                <?php if ($art['published'] == true): ?>
                                                                        <a class="fa fa-check btn unpublish"
                                                                                href="manage_art.php?unpublish=<?php echo $art['id'] ?>">
                                                                        </a>
                                                                <?php else: ?>
                                                                        <a class="fa fa-times btn publish"
                                                                                href="manage_art.php?publish=<?php echo $art['id'] ?>">
                                                                        </a>
                                                                <?php endif ?>
                                                                </td>
                                                        <?php endif ?>
                                <td>
                                    <a class="fa fa-pencil btn edit"
                                       href="create_art.php?edit-art=<?php echo $art['id'] ?>">
                                    </a>
                                </td>
                                <td>
                                <a class="fa fa-trash btn delete"
                                    href="manage_art.php?delete-art=<?php echo $art['id']; ?>">
                                </a>

                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php endif ?>
        </div>
        <!-- End of table-div -->
    </div>
</body>
</html>

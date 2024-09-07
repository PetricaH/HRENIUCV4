<?php
// Include necessary files
include('../config.php');
include(ROOT_PATH . '/admin/includes/admin_functions.php');
include(ROOT_PATH . '/admin/includes/post_functions.php');
include(ROOT_PATH . '/admin/includes/head_section.php');

// Get all topics for the dropdown
$topics = getAllTopics();

// Define a title for the page
?>
<title>Admin | Create Post</title>
</head>
<body>
    <!-- Admin navbar -->
    <?php include(ROOT_PATH . '/admin/includes/navbar.php'); ?>
    <div class="container content">
        <!-- Left side menu -->
        <?php include(ROOT_PATH . '/admin/includes/menu.php'); ?>

        <!-- Middle form to create and edit posts -->
        <div class="action create-post-div">
            <h1 class="page-title">Create/Edit Post</h1>
            <form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL . 'admin/create_post.php'; ?>" >
                <!-- Validation errors for the form -->
                <?php include(ROOT_PATH . '/includes/errors.php') ?>

                <!-- If editing post, the ID is required to identify that post -->
                <?php if ($isEditingPost === true): ?>
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>" >
                <?php endif ?>

                <input type="text" name="title" value="<?php echo $title; ?>" placeholder="Title">
                <label style="float: left; margin: 5px auto 5px;">Featured Image</label>
                <input type="file" name="featured_image">
                <textarea name="body" id="body" cols="30" rows="10"><?php echo $body; ?></textarea>
                <select name="topic_id">
                    <option value="" selected disabled>Choose Topic</option>
                    <?php foreach ($topics as $topic): ?>
                        <option value="<?php echo $topic['id']; ?>">
                            <?php echo $topic['name']; ?>
                        </option>
                    <?php endforeach ?>
                </select>

                <!-- Only admin users can view the publish input field -->
                <?php if ($_SESSION['user']['role'] == "Admin"): ?>
                    <!-- Display checkbox according to whether the post has been published or not -->
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

                <!-- Buttons for saving and publishing -->
                <button type="submit" name="save_post" class="btn">Save as Draft</button>
                <button type="submit" name="publish_post" class="btn">Publish</button>
            </form>
        </div>
        <!-- End of middle form to create and edit -->
    </div>
</body>
</html>

<script>
    CKEDITOR.replace('body');
</script>

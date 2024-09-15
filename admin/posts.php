<?php
include('../config.php');
include(ROOT_PATH . '/admin/includes/admin_functions.php');
include(ROOT_PATH . '/admin/includes/post_functions.php');
include(ROOT_PATH . '/admin/includes/head_section.php');

// Initialize variables
$isEditingPost = false;
$title = $body = '';
$published = false;
$post_id = 0;

// Check if an edit action is required
if (isset($_GET['edit-post'])) {
    $post_id = intval($_GET['edit-post']); // Ensure post_id is an integer
    if ($post_id > 0) {
        editPost($post_id);
        $isEditingPost = true;
    } else {
        die("Invalid post ID");
    }
}

// Get all posts for display
$posts = getAllPosts();
?>
<title>Admin | Manage Digital Marketing Projects</title>
</head>
<body>
    <!-- Admin navbar -->
    <?php include(ROOT_PATH . '/admin/includes/navbar.php'); ?>

    <div class="container content">
        <!-- Left side menu -->
        <?php include(ROOT_PATH . '/admin/includes/menu.php'); ?>

        <!-- Display notification messages -->
        <?php include(ROOT_PATH . '/includes/messages.php'); ?>

        <!-- Middle form to create and edit posts -->
        <?php if ($isEditingPost): ?>
            <div class="action create-post-div">
                <h1 class="page-title">Edit Post</h1>
                <form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL . 'admin/create_post.php'; ?>">
                    <!-- Validation errors for the form -->
                    <?php include(ROOT_PATH . '/includes/errors.php'); ?>

                    <!-- If editing post, the ID is required to identify that post -->
                    <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post_id); ?>">

                    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" placeholder="Title" required>
                    <label style="float: left; margin: 5px auto 5px;">Image</label>
                    <input type="file" name="image">
                    <textarea name="body" id="body" cols="30" rows="10"><?php echo htmlspecialchars($body); ?></textarea>

                    <!-- Assuming you have a function to fetch topics -->
                    <?php $topics = getAllTopics(); ?>
                    <select name="topic_id">
                        <option value="" selected disabled>Choose Topic</option>
                        <?php foreach ($topics as $topic): ?>
                            <option value="<?php echo htmlspecialchars($topic['id']); ?>" <?php if (isset($topic_id) && $topic_id == $topic['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($topic['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Only admin users can view the publish input field -->
                    <?php if ($_SESSION['user']['role'] == "Admin"): ?>
                        <label for="publish">
                            Publish
                            <input type="checkbox" value="1" name="publish" <?php if ($published) echo 'checked'; ?>>
                        </label>
                    <?php endif; ?>

                    <!-- Buttons for saving and publishing -->
                    <button type="submit" name="save_post" class="btn">Save as Draft</button>
                    <button type="submit" name="publish_post" class="btn">Publish</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Display all posts -->
        <div class="table-div" style="width: 80%;">
            <?php if (empty($posts)): ?>
                <h1 style="text-align: center; margin-top: 20px;">No posts in the database.</h1>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <th>No</th>
                        <th>Author</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Published</th>
                        <th>Image</th>
                        <?php if ($_SESSION['user']['role'] == "Admin"): ?>
                            <th><small>Publish</small></th>
                        <?php endif; ?>
                        <th><small>Edit</small></th>
                        <th><small>Delete</small></th>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $key => $post): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($key + 1); ?></td>
                                <td><?php echo htmlspecialchars($post['username']); ?></td>
                                <td>
                                    <a target="_blank" href="<?php echo BASE_URL . 'single_post.php?post-slug=' . htmlspecialchars($post['slug']); ?>">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($post['id']); ?></td>
                                <td><?php echo $post['published'] ? "Yes" : "No"; ?></td>
                                <td><img src="<?php echo BASE_URL . '/uploads/posts/' . htmlspecialchars($post['image']); ?>" alt="" style="height: 60px;"></td>

                                <?php if ($_SESSION['user']['role'] == "Admin"): ?>
                                    <td>
                                        <?php if ($post['published']): ?>
                                            <a class="fa fa-check btn unpublish" href="posts.php?unpublish=<?php echo htmlspecialchars($post['id']); ?>"></a>
                                        <?php else: ?>
                                            <a class="fa fa-times btn publish" href="posts.php?publish=<?php echo htmlspecialchars($post['id']); ?>"></a>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>

                                <td>
                                    <a class="fa fa-pencil btn edit" href="posts.php?edit-post=<?php echo htmlspecialchars($post['id']); ?>"></a>
                                </td>
                                <td>
                                    <a class="fa fa-trash btn delete" href="posts.php?delete-post=<?php echo htmlspecialchars($post['id']); ?>"></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<script>
    CKEDITOR.replace('body');
</script>

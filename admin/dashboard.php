<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
    <title>Admin | Dashboard</title>
</head>
<body>
    <div class="header">
        <div class="logo">
            <a href="<?php echo BASE_URL . 'admin/dashboard.php' ?>">
                <img src="../static/images/wartung-logo.png" class="logo-image">
            </a>
        </div>
        <?php if (isset($_SESSION['user'])): ?>
            <div class="user-info">
                <span><?php echo $_SESSION['user']['username'] ?></span> &nbsp; &nbsp;
                <a href="<?php echo BASE_URL . '/logout.php'; ?>" class="logout-btn">logout</a>
            </div>
        <?php endif ?>
    </div>
    <div class="container dashboar">
        <h1>Welcome Admin</h1>
        <div class="stats">
            <a href="users.php" class="first">
                <span>43</span> <br>
                <span>Newly registered users</span>
            </a>
            <a href="posts.php">
                <span>43</span> <br>
                <span>Published posts</span>
            </a>
            <a>
                <span>43</span> <br>
                <span>Published comments</span>
            </a>
        </div>
        <br><br><br>
        <div class="buttons">
            <a href="users.php">Add Users</a>
            <a href="posts.php">Add Posts</a>
            <a href="create_art.php">Create Art Post</a>
        </div>
    </div>
</body>
</html>

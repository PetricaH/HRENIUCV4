<?php 
include('config.php'); 
include('includes/public_functions.php'); 

// Fetch all artworks under a particular category
if (isset($_GET['category'])) {
    $category_id = intval($_GET['category']); // Ensure integer value for category ID
    $artworks = getPublishedArtworksByCategory($category_id); // Fetch all artworks by category
} else {
    $artworks = []; // Initialize empty array if no category is provided
}

// Fetch all categories
$categories = getAllArtCategories(); 

// Function to get category name by ID
function getArtCategoryNameById($id) {
    global $conn;
    $sql = "SELECT name FROM art_categories WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    $category = mysqli_fetch_assoc($result);
    return $category['name'];
}
?>

<?php include('includes/head_section.php'); ?>
<title>ArtGallery | Artworks</title>
</head>
<body>
    <div class="container">
        <!-- Navbar -->
        <?php include(ROOT_PATH . '/includes/navbar.php'); ?>
        <!-- // Navbar -->

        <div class="content">
            <h2 class="content-title">
                Artworks in <u><?php echo htmlspecialchars(getArtCategoryNameById(intval($_GET['category']))); ?></u>
            </h2>
            <hr>

            <?php if (!empty($artworks)): ?>
                <?php foreach ($artworks as $artwork): ?>
                    <div class="post" style="margin-left: 0px;">
                        <img src="<?php echo BASE_URL . '/uploads/art/' . htmlspecialchars($artwork['art_image']); ?>" class="post_image" alt="<?php echo htmlspecialchars($artwork['name']); ?>">
                        <a href="single_art.php?art-id=<?php echo htmlspecialchars($artwork['name']); ?>">
                            <div class="post-info">
                                <h3><?php echo htmlspecialchars($artwork['name']); ?></h3>
                                <div class="info">
                                    <span><?php echo date("F j, Y", strtotime($artwork["created_at"])); ?></span>
                                    <span class="read_more">View Artwork</span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach ?>
            <?php else: ?>
                <p>No artworks found in this category.</p>
            <?php endif ?>
        </div>
        <!-- // Content -->
    </div>
    <!-- // Container -->

    <!-- Footer -->
    <?php include(ROOT_PATH . '/includes/footer.php'); ?>
    <!-- // Footer -->
</body>
</html>

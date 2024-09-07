<?php 
include('config.php'); 
include('includes/public_functions.php'); 

// Fetch a single artwork by its slug
if (isset($_GET['art-slug'])) {
    $artwork = getArtwork($_GET['art-slug']);
} else {
    header("Location: " . BASE_URL); // Redirect to homepage if no slug is provided
    exit();
}

// Fetch all categories
$categories = getAllArtCategories(); 
?>

<?php include('includes/head_section.php'); ?>
<title><?php echo htmlspecialchars($artwork['name']); ?> | ArtGallery</title>
</head>
<body>
    <div class="container">
        <!-- Navbar -->
        <?php include(ROOT_PATH . '/includes/navbar.php'); ?>
        <!-- // Navbar -->

        <div class="content">
            <!-- Artwork details -->
            <div class="artwork-wrapper">
                <div class="full-artwork-div">
                    <?php if ($artwork['published'] == false): ?>
                        <h2 class="artwork-title">Sorry... This artwork is not available</h2>
                    <?php else: ?>
                        <h2 class="artwork-title"><?php echo htmlspecialchars($artwork['name']); ?></h2>
                        <img src="<?php echo BASE_URL . '/static/images/' . htmlspecialchars($artwork['art_image']); ?>" alt="<?php echo htmlspecialchars($artwork['name']); ?>" class="artwork-image">
                        <div class="artwork-description-div">
                            <?php echo html_entity_decode(htmlspecialchars($artwork['description'])); ?>
                        </div>
                        <div class="artwork-category">
                            <strong>Category:</strong> <?php echo htmlspecialchars($artwork['category']['name']); ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>

            <!-- Artwork sidebar -->
            <div class="artwork-sidebar">
                <div class="card">
                    <div class="card-header">
                        <h2>Categories</h2>
                    </div>
                    <div class="card-content">
                        <?php foreach ($categories as $category): ?>
                            <a href="<?php echo BASE_URL . 'filtered_arts.php?category=' . htmlspecialchars($category['id']); ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </a>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
            <!-- // Artwork sidebar -->
        </div>
        <!-- // Content -->
    </div>
    <!-- // Container -->

    <!-- Footer -->
    <?php include(ROOT_PATH . '/includes/footer.php'); ?>
    <!-- // Footer -->
</body>
</html>

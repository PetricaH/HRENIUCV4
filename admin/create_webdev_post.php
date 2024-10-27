<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/webdev_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $conn;

    // Sanitize input for title and description
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    
    // Get the selected technology IDs (no need to use htmlspecialchars here)
    $technologyIds = $_POST['technologies'] ?? []; // This will be an array

    // File upload handling 
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/projects/";
    $target_file = $target_dir . basename($_FILES["project_image"]["name"]);
    
    if (move_uploaded_file($_FILES["project_image"]["tmp_name"], $target_file)) {
        // Insert project data into database
        $image = basename($_FILES["project_image"]["name"]); // Store just the image name
        
        if (addProject($title, $description, $image, $technologyIds)) {
            echo "New webdev project added successfully!";
        } else {
            echo "Error adding project.";
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
<title>Admin | Create WebDev Post</title>
</head>
<body>
    <!-- admin navbar -->
    <?php include(ROOT_PATH . '/admin/includes/navbar.php'); ?>
    <div class="container content">
        <!-- left side menu -->
        <?php include(ROOT_PATH . '/admin/includes/menu.php'); ?>

        <!-- middle form - to create and edit webdev posts -->
        <?php
        $techs = getAllTechnologies(); // Function to retrieve all technologies from the database
        ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="title">Project Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Project Description:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="technologies">Select Technologies Used:</label>
            <div id="technologies" style="display: flex; flex-wrap: wrap;">
                <?php 
                $techs = getAllTechnologies(); // Fetch technologies from database
                foreach ($techs as $tech): ?>
                    <div style="margin: 5px;">
                        <input type="checkbox" name="technologies[]" id="tech-<?php echo $tech['id']; ?>" value="<?php echo $tech['id']; ?>">
                        <label for="tech-<?php echo $tech['id']; ?>"><?php echo $tech['name']; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>


            <label for="project_image">Upload Project Image:</label>
            <input type="file" name="project_image" id="project_image" required>

            <button type="submit">Add Project</button>
        </form>
    </div>
</body>
</html>
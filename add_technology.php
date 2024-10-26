<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
<?php
require_once('../config.php');

if (isset($_POST['tech_name']) && isset($_FILES['tech_logo'])) {
    $tech_name = $_POST['tech_name'];
    $logo = $_FILES['tech_logo'];

    // Handle file upload
    $target_dir = "uploads/technologies/";
    $target_file = $target_dir . basename($logo["name"]);
    
    if (move_uploaded_file($logo["tmp_name"], $target_file)) {
        // Insert into database
        $query = "INSERT INTO technologies (name, logo) VALUES ('$tech_name', '$target_file')";
        if (mysqli_query($conn, $query)) {
            echo "Technology added successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<title>Admin | Add Technology</title>
</head>
<body>
    <?php include(ROOT_PATH . '/admin/includes/navbar.php'); ?>
    <div class="container content">
        <?php include(ROOT_PATH . '/admin/includes/menu.php'); ?>
        
        <h2>Add New Technology</h2>
        <form action="add_technology.php" method="POST" enctype="multipart/form-data">
            <label for="tech_name">Technology Name:</label>
            <input type="text" name="tech_name" id="tech_name" required>
            <br>

            <label for="tech_logo">Upload Technology Logo:</label>
            <input type="file" name="tech_logo" id="tech_logo" required>
            <br>

            <button type="submit" name="add_technology">Add Technology</button>
        </form>
    </div>
</body>
</html>

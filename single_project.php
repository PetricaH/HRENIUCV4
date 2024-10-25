<?php
require_once('config.php');
if (isset($_GET['project-id'])) {
    $project_id = $_GET['project-id'];
    $project = getProjectById($project_id);
} else {
    // Redirect or show error if no project id is provided
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $project['title']; ?></title>
</head>
<body>
    <div class="single-project-container">
        <div class="project-description">
            <h1><?php echo $project['title']; ?></h1>
            <p><?php echo $project['description']; ?></p>
        </div>
        <div class="project-screenshot">
            <img src="<?php echo BASE_URL . '/uploads/projects/' . $project['project_image']; ?>" alt="<?php echo $project['title']; ?>">
        </div>
    </div>
</body>
</html>

<?php
// Function to get project details by ID
function getProjectById($project_id) {
    global $conn;
    $sql = "SELECT * FROM projects WHERE id = $project_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}
?>

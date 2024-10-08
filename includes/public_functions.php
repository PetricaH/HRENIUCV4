<?php
// Function to get all published artworksg
function getPublishedArtworks() {
    global $conn;
    $sql = "SELECT a.*, ac.name AS category_name, ac.id AS category_id
            FROM art a
            JOIN art_categories ac ON a.art_category_id = ac.id
            WHERE a.published=true";
    $result = mysqli_query($conn, $sql);
    $artworks = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($artworks as &$artwork) { 
        // --
        // the & before $artwork is needed to create a reference to the origina array element
        //  without the & when you iterate over $artworks without & PHP copies the array element into $artwork. THis means that any changes you make to $artwork inside the loop won't affect the original array ($artworks)
        // --
        // attach category information
        $artwork['category'] = [
            'id' => $artwork['category_id'],
            'name' => $artwork['category_name']
        ];
    }
    
    return $artworks;
}

// Function to get a single artwork by its slug
function getArtwork($slug) {
    global $conn;
    // get single artwork slug
    $artwork_slug = $_GET['artwork-slug'];
    $sql = "SELECT * FROM art WHERE slug='$artwork_slug' AND published=true";
    $result = mysqli_query($conn, $sql);
    
    // fetch query results as associative array
    $artwork = mysqli_fetch_assoc($result);
    if ($artwork) {
        // get the art category to which the artwork belongs
        $artwork['art_category_id'] = getArtCategory($artwork['id']);
    }
    return $artwork;
}

// Function to get all artworks by category
function getPublishedArtworksByCategory($category_id) {
    global $conn;
    $category_id = intval($category_id); // Ensure category_id is an integer
    $sql = "SELECT * FROM art WHERE art_category_id=$category_id AND published=true";
    $result = mysqli_query($conn, $sql);
    $artworks = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $final_artworks = array();
    foreach ($artworks as $artwork) {
        $artwork['category'] = getArtCategory($artwork['art_category_id']);
        array_push($final_artworks, $artwork);
    }
    return $final_artworks;
}

// Function to get art category details by category ID
function getArtCategory($category_id) {
    global $conn;
    $category_id = intval($category_id); // Ensure category_id is an integer
    $sql = "SELECT * FROM art_categories WHERE id=$category_id";
    $result = mysqli_query($conn, $sql);
    $category = mysqli_fetch_assoc($result);
    return $category;
}

// Function to get all art categories
function getAllArtCategories() {
    global $conn;
    $sql = "SELECT * FROM art_categories";
    $result = mysqli_query($conn, $sql);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $categories;
}

// function to get all published webdev projects
function getPublishedWebdevProjects() {
    global $conn;
    $sql = "SELECT a.*, ac.name AS category_name, ac.id AS category_id
        FROM webdev a
        JOIN webdev_project_categories ac ON a.project_category_id = ac.id
        WHERE a.published=true";
    $result = mysqli_query($conn, $sql);
    $webdevprojects = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($webdevprojects as &$webdevproject) {
        // attach category information
        $webdevproject['category'] = [
            'id' => $webdevproject['category_id'],
            'name' => $webdevproject['category_name']
        ];
    }

    return $webdevprojects;
}

//function to get a single web dev project by its slug
function getWebdevProject($slug) {
    global $conn;
    $slug = mysqli_real_escape_string($conn, $slug);
    $sql = "SELECT * FROM webdev WHERE slug='$slug' AND published=true";
    $result = mysqli_query($conn, $sql);
    $webdevproject = mysqli_fetch_assoc($result);
    if ($webdevproject) {
        $webdevproject['category'] = getWebdevProjectCategory($webdevproject['project_category_id']);
    }
    return $webdevproject;
}

// function to get webdev project category details by category id
function getWebdevProjectCategory($category_id) {
    global $conn;
    $category_id = intval($category_id); //ensure category_id is an integer
    $sql = "SELECT * FROM webdev_project_categories WHERE id=$category_id";
    $result = mysqli_query($conn, $sql);
    $category = mysqli_fetch_assoc($result);
    return $category;
}

//function to get all webdev project categories
function getAllWebdevProjectCategories() {
    global $conn;
    $sql = "SELECT * FROM webdev_project_categories";
    $result = mysqli_query($conn, $sql);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $categories;
}
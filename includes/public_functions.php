<?php

function getPublishedPosts() {
    // use global $conn object in function
    global $conn;
    $sql = "SELECT * FROM posts WHERE published=true";
    $result = mysqli_query($conn, $sql);

    // fetch all posts as an associative array called $posts
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $final_posts = array();
    foreach ($posts as $post) {
        $post['topic'] = getPostTopic($post['id']);
        array_push($final_posts, $post);
    }
    return $final_posts;
}

function getPostTopic($post_id){
    global $conn;
    $sql = "SELECT * FROM topics WHERE id=
                    (SELECT topic_id FROM post_topic WHERE post_id=$post_id) LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic;
}

// this function returns all posts under a topic
function getPublishedPostsByTopic($topic_id) {
    global $conn;
    $sql = "SELECT * FROM posts ps
                    WHERE ps.id IN
                    (SELECT pt.post_id FROM post_topic pt
                            WHERE pt.topic_id=$topic_id GROUP BY pt.post_id
                            HAVING COUNT(1) = 1)";
    $result = mysqli_query($conn, $sql);
// fetch all posts as an associative array called $posts
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $final_posts = array();
    foreach ($posts as $post) {
        $post['topic'] = getPostTopic($post['id']);
        array_push($final_posts, $post);
    }
    return $final_posts;
}

// this function returns name by topic id
function getTopicNameById($id) {
    global $conn;
    $sql = "SELECT name FROM topics WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic['name'];
}

//returns a single post
function getPost($slug){
    global $conn;
    // get single post slug
    $post_slug = $_GET['post-slug'];
    $sql = "SELECT * FROM posts WHERE slug='$post_slug' AND published=true";
    $result = mysqli_query($conn, $sql);

    //fetch query results as associative array
    $post = mysqli_fetch_assoc($result);
    if ($post) {
        // get the topic to which this post belongs
        $post['topic'] = getPostTopic($post['id']);
    }
    return $post;
}

// function to return all topics
function getAllTopics() {
    global $conn;
    $sql = "SELECT * FROM topics";
    $result = mysqli_query($conn, $sql);
    $topics = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $topics;
}

// Function to get all published artworks
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
    $slug = mysqli_real_escape_string($conn, $slug);
    $sql = "SELECT * FROM art WHERE slug='$slug' AND published=true";
    $result = mysqli_query($conn, $sql);
    $artwork = mysqli_fetch_assoc($result);
    if ($artwork) {
        $artwork['category'] = getArtCategory($artwork['art_category_id']);
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
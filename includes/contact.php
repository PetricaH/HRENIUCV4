<?php 
session_start();

// include captcha generation code
require 'captcha.php';

// form submission logic
$errors = [];
$success = false;

// chech if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // validate name
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }

    // validate email
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    if (empty($email)) {
        $erorrs['email'] = 'Email is required';
    } elseif (!$email) {
        $errors['email'] = 'Invalid email adress'; 
    }

    // validate subject 
    
}
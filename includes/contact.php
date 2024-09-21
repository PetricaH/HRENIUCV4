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
    $subject = filter_var(trim($_POST['message']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (empty($email)) {
        $errors['message'] = 'Message is  required';
    }

    // validate captcha
    if (empty($_POST['captcha']) || $_POST['captcha'] != $_SESSION['captcha_code']) {
        $errors['captcha'] = 'Invalid CAPTCHA.';
    }

    // if no errors, send email
    if (empty($errors)) {
        // prepare email headers and message
        $to = "mail@hreniucpetrica.ro";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $message_body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

        // send email to
        if (mail($to, $subject, $message_body, $headers)) {
            $success = true;
        } else {
            $errors['send'] = 'Failed to send the email. Please try again';
        }
    }
}
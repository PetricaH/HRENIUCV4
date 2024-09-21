<?php 

session_start();

// generate a random string for the captcha
$captcha_code = '';
$captcha_lenght = 6;
for ($i = 0; $i < $captcha_lenght; $i++) {
    $captcha_code .= chr(rand(97, 122)); // generate random lowercases
}

// store the captcha sting for in the session for later validation
$_SESSION['captcha_text'] = $captcha_code;

// create the captcha image
$captcha_image = imagecreate(150, 50);
$background_color = imagecolorallocate($captcha_image, 255, 255, 255);
$text_color = imagecolorallocate($captcha_image, 0, 0, 0);
$line_color = imagecolorallocate($captcha_image, 64, 64, 64);

// add the captcha text
imagestring($captcha_image, 5, 35, 15, $captcha_code, $text_color);

// add some lines to make it harder for the bots
for ($i = 0; $i < 3; $i++) {
    imageline($captcha_image, 0, rand(0, 50), 150, rand(0, 50), $line_color);
}

// set the header to display image
header('Content-Type: image/png');
imagepng($captcha_image);
imagedestroy($captcha_image);
?>
$(document).ready(function() {
    $("#contactForm").validate({
        rules: {
            name: "required",
            email: {
                required: true,
                email: true
            }, 
            message: "required",
            captcha: "required"
        },
        messages: {
            name: "Please enter your name",
            email: "Please enter your valid email adress",
            message: "Please write your message",
            captcha: "Please enter the captcha"
        }
    });
});
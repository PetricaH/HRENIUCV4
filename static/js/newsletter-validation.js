function validate(email) {
    // Regular expression pattern for email validation
    var reg = /^[a-zA-Z0-9]+(\.[_a-zA-Z0-9]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,15})$/;

    // Test the email value against the pattern
    if (!reg.test(email)) {
        alert('Invalid Email Address');
        return false; // Invalid email
    }
    return true; // Valid email
}

// Attach the function to the form's submit event
document.getElementById('newsletterForm').onsubmit = function() {
    var email = document.getElementById('email-input-newsletter').value; // Get the email input value

    // Call validate and return false to prevent submission if invalid
    return validate(email);
};

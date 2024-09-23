$(document).ready(function() {
    $('#newsletterForm').on('submit', function(e) {
        var email = $('email').val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Please enter a valid email adress.');
        }
    });
});
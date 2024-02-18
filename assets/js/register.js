$(document).ready(function() {
    //show signup form when clicking on the registration link
    $('#signup').click(function() {
        $('#signup-form').slideUp('slow',function() {
            $('#signin-form').slideDown('slow');
        })
    });

    $('#signin').click(function() {
        $('#signin-form').slideUp('slow',function() {
            $('#signup-form').slideDown('slow');
        })
    });
});
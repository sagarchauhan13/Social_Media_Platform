$(document).ready(function() {
    $('#submit').click(function(){
        if($('#user_password').val() != $('#confirm_password').val()){
            alert("Password Does Not Matched !!");
            event.preventDefault();
        }
    });
});
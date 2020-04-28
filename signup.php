<?php
session_start();
require_once 'class.php';
$operation = new Operations();
if(isset($_POST['submit'])){
$target_dir = "web_image/";
$target_file = $target_dir.basename($_FILES["user_photo"]["name"]);
$operation->user_name = $_POST['user_name'];
$operation->user_email=$_POST['user_email'];
$operation->user_password=$_POST['user_password'];
$operation->user_mobile=$_POST['user_mobile'];
$operation->user_photo=$_FILES["user_photo"]["name"];
move_uploaded_file($_FILES["user_photo"]["tmp_name"], $target_file);
$check_email_var = $operation->check_email($operation->user_email);
if($check_email_var){
    $operation->Insert();
   header("location:timeline.php");
} else {
    $msg = '<font color="red">E-mail Taken, Try Another</font>';
}
if($operation->user_id){
     $_SESSION['user_name'] = $operation->user_name;
}
} else {
    $_SESSION['user_name'] = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="/sagar/task3/jquery.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign up</title>
    <link rel="stylesheet" href="/sagar/task3/template/template/fonts/material-icon/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="/sagar/task3/template/template/css/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Registration</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <meta charset=utf-8 />
</head>
<body>
    <!-- Sign up form -->
    <section class="signup">
        <div class="container">
            <div class="signup-content">
                <div class="signup-form">
                    <h2 class="form-title">Create Your Account</h2>
                    <form method="post" class="register-form" id="register-form"name="register-form" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="hidden" id="user_id" name="user_id" value="">
                            <label for="user_name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                            <input type="text" name="user_name" id="user_name" value="<?=$operation->user_name?>" placeholder="Your Name" required=""/>
                        </div>
                        <div class="form-group">
                            <label for="user_email"><i class="zmdi zmdi-email"></i></label>
                            <input type="email" name="user_email" id="user_email" value="<?=$operation->user_email?>" placeholder="Your Email" required=""/>
                            <div id="email"></div>
                        </div>
                        <div>
                        <?php if(isset($msg)){ echo $msg; } ?>
                        </div>
                        <div class="form-group">
                            <label for="user_password"><i class="zmdi zmdi-lock"></i></label>
                            <input type="password" name="user_password" id="user_password" placeholder="Password" required=""/>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password"><i class="zmdi zmdi-lock"></i></label>
                            <input type="password" name="confirm_password" id="confirm_password" placeholder=" Confirm Password" required=""/>
                        </div>
                        <div id="password_alert">
                            
                        </div>
                        <div class="form-group">
                            <label for="user_mobile"><i class="zmdi zmdi-smartphone-iphone"></i></label>
                            <input type="number" name="user_mobile" value="<?=$operation->user_mobile?>" id="user_mobile" placeholder="Mobile" required="" min="10"/>
                        </div>
                        <div class="form-group">
                            <label for="user_photo"><i class="zmdi zmdi-image"></i></label>
                            <input type="file" name="user_photo" id="user_photo" required="" accept="image/*"/>
                        </div>
                        <div class="form-group form-button">
                        <input type="submit" name="submit" id="submit" class="form-submit" value="Register"/>
                        </div>
                    </form>
                </div>
                <div class="signup-image">
                    <figure>
                        <img src="/sagar/task3/template/template/images/signup-image.jpg" alt="sing up image">
                    </figure>
                    <a href="login.php" class="signup-image-link">Login Here</a>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
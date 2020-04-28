<?php
session_start();
require_once 'class.php';
$operation = new Operations();
if(isset($_POST['signin'])){
    $operation->user_email = $_POST['user_email'];
    $operation->user_password = $_POST['user_password'];
    $login_var = $operation->Login($operation->user_email,$operation->user_password,$operation->user_id,$operation->user_name);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/sagar/task3/template/template/fonts/material-icon/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href=" /sagar/task3/template/template/css/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <section class="sign-in">
        <div class="container">
            <div class="signin-content">
                <div class="signin-image">
                    <figure><img src="/sagar/task3/template/template/images/signin-image.jpg" alt="sing up image"></figure>
                    <a href="signup.php" class="signup-image-link">Create an account</a>
                </div>
                <div class="signin-form">
                    <h2 class="form-title">Log in</h2>
                    <form method="post" class="register-form" id="login-form">
                        <div class="form-group">
                            <label for="user_email"><i class="zmdi zmdi-email"></i></label>
                            <input type="email" name="user_email" id="user_email" value="<?=$operation->user_email?>" placeholder="E-mail" required="" />
                        </div>
                        <div class="form-group">
                            <label for="user_password"><i class="zmdi zmdi-lock"></i></label>
                            <input type="password" name="user_password" id="user_password" placeholder="Password" required=""/>
                        </div>
                        <div id="wrong_password">
                            <?php if(isset($login_var)){
                            echo '<font color="red">Invalid E-mail or Password</font>';
                            } 
                            ?>
                        </div>
                        <div class="form-group form-button">
                            <input type="submit" name="signin" id="signin" class="form-submit" value="Log in"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
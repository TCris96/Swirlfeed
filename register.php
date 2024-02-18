<?php
require 'config/config.php';
require 'includes/register_handlers/form_handler.php';
require 'includes/register_handlers/login_handler.php';

?>

<html>
    <head>
        <title>Welcome to Swirlfeed!</title>
        <link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="assets/js/register.js"></script>
    </head>
    <body>
        <?php 
            if (isset($_POST['register_button'])) {
                echo '
                <script>
                $(document).ready(function() {
                    $("#signin-form").hide();
                    $("#signup-form").show();
                });
                </script>';
            }
        ?>
        <div class="wrapper">
            <div class="login_box">
                <div class="login_header">
                    <h1>Swirlfeed!</h1>
                    Log-in or sign-up below!
                </div>
                <div id="signin-form">
                    <form action="register.php" method="POST">
                        <input type="email" name="login_email" placeholder="Email Address" value="<?php 
                            if (isset($_SESSION['login_email'])) {
                                echo $_SESSION['login_email'];
                            }
                        ?>" required>
                        <br>
                        <input type="password" name="login_password" placeholder="Password" required>
                        <br>
                        <input type="submit" name="login_button" value="Login">
                        <br>
                        <?php if (RegError::containsError('login_failed'))
                            echo RegError::getError('login_failed')->getMessage() . '<br>'; ?>
                        <a href="#" id="signin" class="sigin">Don't have an account? Register here!</a>
                    </form>
                </div>
                <div id="signup-form">
                    <form action="register.php" method="POST">
                        <input type="text" name="reg_fname" placeholder="First Name" value="<?php 
                            if (isset($_SESSION['reg_fname'])) {
                                echo $_SESSION['reg_fname'];
                            }
                        ?>" required>
                        <br>
                        <?php if (RegError::containsError('incorrect_fname'))
                            echo RegError::getError('incorrect_fname')->getMessage() . '<br>'; ?>

                        <input type="text" name="reg_lname" placeholder="Last Name" value="<?php 
                            if (isset($_SESSION['reg_lname'])) {
                                echo $_SESSION['reg_lname'];
                            }
                        ?>" required>
                        <br>
                        <?php if (RegError::containsError('incorrect_lname'))
                            echo RegError::getError('incorrect_lname')->getMessage() . '<br>'; ?>
                        
                        <input type="email" name="reg_email" placeholder="Email" value="<?php 
                            if (isset($_SESSION['reg_email'])) {
                                echo $_SESSION['reg_email'];
                            }
                        ?>" required>
                        <br>
                        <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php 
                            if (isset($_SESSION['reg_email2'])) {
                                echo $_SESSION['reg_email2'];
                            }
                        ?>" required>
                        <br>
                        <?php 
                        if (RegError::containsError('invalid_email'))
                            echo RegError::getError('invalid_email')->getMessage() . '<br>'; 
                        else if (RegError::containsError('emails_not_matching'))
                            echo RegError::getError('emails_not_matching')->getMessage() . '<br>'; 
                        else if (RegError::containsError('email_exists'))
                            echo RegError::getError('email_exists')->getMessage() . '<br>'; 
                        ?>
                        
                        <input type="password" name="reg_password" placeholder="Password" required>
                        <br>
                        <input type="password" name="reg_password2" placeholder="Confirm Password" required>
                        <br>
                        <?php 
                        if (RegError::containsError('incorrect_password'))
                            echo RegError::getError('incorrect_password')->getMessage() . '<br>'; 
                        else if (RegError::containsError('passwords_not_matching'))
                            echo RegError::getError('passwords_not_matching')->getMessage() . '<br>'; 
                        else if (RegError::containsError('incorrect_password_format'))
                            echo RegError::getError('incorrect_password_format')->getMessage(); 
                        ?>

                        <input type="submit" name="register_button" value="Register">
                        <br>
                        <?php if (isset($signup_element)) echo $signup_element; ?>
                        <a href="#" id="signup" class="sigup">Already have an account? Sign in here!</a>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
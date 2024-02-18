<?php
require 'RegError.php';
require 'Utils.php';

//Declaring values to prevent errors
$fname = '';
$lname = '';
$email = '';
$email2 = '';
$password = '';
$password2 = '';
$date = '';
$username = '';
$signup_element = '';

if (isset($_POST['register_button'])) {
    $fname = Utils::removeTags($_POST['reg_fname']);
    $fname = Utils::sanitazeValue($fname);
    $_SESSION['reg_fname'] = $fname;
    
    $lname = Utils::removeTags($_POST['reg_lname']);
    $lname = Utils::sanitazeValue($lname);
    $_SESSION['reg_lname'] = $lname;
    
    $email = Utils::removeTags($_POST['reg_email']);
    $email = Utils::sanitazeValue($email);
    $_SESSION['reg_email'] = $email;

    $email2 = Utils::removeTags($_POST['reg_email2']);
    $email2 =Utils:: sanitazeValue($email2);
    $_SESSION['reg_email2'] = $email2;
    
    $password = Utils::removeTags($_POST['reg_password']);
    
    $password2 = Utils::removeTags($_POST['reg_password2']);

    $date = date('Y-m-d'); // Current date

    if ($email == $email2) {
        //Check if the email is in valid form
        $validatedEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($validatedEmail) {
            $email = $validatedEmail;
        } else {
            new RegError('invalid_email', RegError::INCORRECT_EMAIL);
        }

        //Check if the email already exists
        $email_check = mysqli_query($connection, "SELECT email FROM users WHERE email='$email'");

        //Count the number of rows
        $num_rows = mysqli_num_rows($email_check);

        if ($num_rows > 0) {
            new RegError('email_exists', RegError::EMAIL_EXISTS);
        }
    } else {
        new RegError('emails_not_matching', RegError::EMAILS_NOT_MATCHING);
    }

    if (Utils::validateName($fname)) {
        new RegError('incorrect_fname', RegError::INCORRECT_FIRST_NAME);
    }
    
    if (Utils::validateName($lname)) {
        new RegError('incorrect_lname', RegError::INCORRECT_LAST_NAME);
    }

    if ($password != $password2) {
        new RegError('passwords_not_matching', RegError::PASSWORD_NOT_MATCHING);
    }

    if (Utils::validatePassword($password)) {
        new RegError('incorrect_password', RegError::INCORRECT_PASSWORD);
    }

    if (preg_match('/[^A-Za-z0-9]/', $password)) {
        new RegError('incorrect_password_format', RegError::INCORRECT_PASSWORD_FORMAT);
    }

    if (count(RegError::getErrorArray()) == 0) {
        $password = md5($password);

        $profile_pic = 'assets/images/profile/profile.png';
        $username = strtolower($fname . '_' . $lname);

        $username_query = mysqli_query($connection, "SELECT username FROM users WHERE username='$username'");

        $index = 0;

        while (mysqli_num_rows($username_query) != 0 ) {
            $index++;
            $username = $username . '_' . $index;
            
            $username_query = mysqli_query($connection, "SELECT username FROM users WHERE username='$username'");
        }
        
        $insert_query = mysqli_query($connection,
        "INSERT INTO users VALUES ('', '$fname', '$lname', '$email', '$password', '$username', 0, 0, '$profile_pic', '$date', 'no', ',')");

        $signup_element = '<span style="color: #14C800;">You are signed up!</span>';

        Utils::clearSessionFormVariables();
    }
}

?>
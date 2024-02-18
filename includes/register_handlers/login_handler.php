<?php
if (isset($_POST['login_button'])) {
    $email = filter_var($_POST['login_email'], FILTER_SANITIZE_EMAIL);
    $_SESSION['login_email'] = $email;

    $password = md5($_POST['login_password']);

    $check_login_query = mysqli_query($connection, "SELECT * FROM users WHERE email='$email' AND password='$password'");

    if (mysqli_num_rows($check_login_query) == 1) {
        $user = mysqli_fetch_array($check_login_query);


        $_SESSION['username'] = $user['username'];

        //if the account is closed, when user login reopen the account
        echo $user['account_closed'];

        header("Location: index.php");
        exit();
    } else {
        new RegError('login_failed', RegError::LOGIN_FAILED);
    }
}
?>
<?php
require 'config/config.php';

if (isset($_SESSION['username'])) {
    $loginUsername = $_SESSION['username'];
    $user_detail_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$loginUsername'");
    $user = mysqli_fetch_array($user_detail_query);
} else {
    header('Location: register.php');
}
?>
<html>
    <head>
        <title>Welcome to Swirlfeed!</title>
        <!--JAVASCRIPT -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="assets/js/bootstrap.js"></script>

        <!--CSS -->
        <script src="https://kit.fontawesome.com/adf2f82aef.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <a href="index.php">Swirlfeed!</a>
        </div>
        <nav>
            <a href="#">
                <?php echo $user['first_name']; ?>
            </a>
            <a href="index.php">
                <i class="fa-solid fa-house-user"></i>
            </a>
            <a href="#">
                <i class="fa-regular fa-message"></i>
            </a>
            <a href="#">
                <i class="fa-regular fa-bell"></i>
            </a>
            <a href="#">
                <i class="fa-solid fa-users"></i>
            </a>
            <a href="#">
                <i class="fa-solid fa-gear"></i>
            </a>
            <a href="includes/handlers/logout.php">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </a>
        </nav>
    </div>
    <div class="wrapper">

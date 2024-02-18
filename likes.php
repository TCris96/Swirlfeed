<?php
    require_once(__DIR__ . '/includes/utils/DateTimeUtility.php');
    require 'config/config.php';
    include('includes/classes/User.php');
    include('includes/classes/Post.php');

    if (isset($_SESSION['username'])) {
        $loginUsername = $_SESSION['username'];
        $user_detail_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$loginUsername'");
        $user = mysqli_fetch_array($user_detail_query);
    } else {
        header('Location: register.php');
    }

    if (isset($_GET['post_id'])) {
        $postId = $_GET['post_id'];
    }
?>

<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <style type="text/css">
        * {
            font-family: Arial, Helvetica, Sans-serif;
        }
        body {
            background-color: #fff;
        }

        form {
            position: absolute;
            top: 0;
        }
    </style>
    <?php
    $postDetailsQuery = mysqli_query($connection, "SELECT likes, added_by FROM posts WHERE id='$postId'");
    $postDetails = mysqli_fetch_array($postDetailsQuery);

    $likesNo = $postDetails['likes'];
    $userLiked = $postDetails['added_by'];

    $user = new User($connection, $userLiked);
    $totalUserLikes = $user->likesNumber;

    //Like button triggered
    if (isset($_POST['like_button'])) {
        $likesNo++;
        $updateLikesQuery = mysqli_query($connection, "UPDATE posts SET likes='$likesNo' WHERE id='$postId'");

        $totalUserLikes++;
        $updateUserLikesQuery = mysqli_query($connection, "UPDATE users SET num_likes='$totalUserLikes' WHERE username='$userLiked'");

        $insertLikesQuery = mysqli_query($connection, "INSERT INTO likes VALUES('', '$loginUsername', '$postId')");
    }

    //Unlike button triggered
    if (isset($_POST['unlike_button'])) {
        $likesNo--;
        $updateLikesQuery = mysqli_query($connection, "UPDATE posts SET likes='$likesNo' WHERE id='$postId'");

        $totalUserLikes--;
        $updateUserLikesQuery = mysqli_query($connection, "UPDATE users SET num_likes='$totalUserLikes' WHERE username='$userLiked'");

        $insertLikesQuery = mysqli_query($connection, "DELETE FROM likes WHERE username='$loginUsername' AND post_id='$postId'");
    }

    $likesQuery = mysqli_query($connection, "SELECT * FROM likes WHERE username='$loginUsername' AND post_id='$postId'");
    $likesCheck = mysqli_num_rows($likesQuery);

    if ($likesCheck > 0) {
        echo '<form action="likes.php?post_id=' . $postId . '" method="POST">
            <input type="submit" class="comment_like" name="unlike_button" value="Unlike">
            <div class="likes_number">
                ' . $likesNo . ' Likes
            </div>
        </form>';
    } else {
        echo '<form action="likes.php?post_id=' . $postId . '" method="POST">
            <input type="submit" class="comment_like" name="like_button" value="Like">
            <div class="likes_number">
                ' . $likesNo . ' Likes
            </div>
        </form>';
    }
    ?>

</body>
</html>

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
            font-size: 12px;
            font-family: Arial, Helvetica, Sans-serif;
        }
    </style>

    <script>
        function toggleComments() {
            $('.comments-section').toggle();
        }
    </script>

    <?php
        if (isset($_GET['post_id'])) {
            $postId = $_GET['post_id'];
        }

        $postQuery = mysqli_query($connection, "SELECT added_by, user_to FROM posts WHERE id='$postId'");
        $result = mysqli_fetch_array($postQuery);
        
        $postedTo = $result['added_by'];

        if (isset($_POST['postComment' . $postId])) {
            $dateTime = date('Y-m-d H:i:s');
            $postBody = $_POST['post_body'];
            $postBody = mysqli_escape_string($connection, $postBody);

            $commentQuery = mysqli_query($connection, "INSERT INTO comments VALUES('', '$postBody', '$postedTo', '$loginUsername', '$dateTime', 'no', '$postId')");

            echo '<p>Comment posted!</p>';
        }
    ?>
    <form action="comments.php?post_id=<?php echo $postId; ?>" name="postComments<?php echo $postId; ?>" id="postComments" method="POST">
        <textarea name="post_body"></textarea>
        <input type="submit" name="postComment<?php echo $postId; ?>" value="Post">
    </form>

    <!--Loading comments -->
    <?php
        $getCommentsQuery = mysqli_query($connection, "SELECT * FROM comments WHERE post_id='$postId' ORDER BY id ASC");
        $count = mysqli_num_rows($getCommentsQuery);

        if ($count > 0) {
            while ($comment = mysqli_fetch_array($getCommentsQuery)) {
                $CommentBody = $comment['post_body'];
                $postedBy = $comment['posted_by'];
                $dateTime = $comment['date_added'];
                $postedTo = $comment['posted_to'];

                $dateMessage = DateTimeUtility::getDateTimeMessage($dateTime);

                $user = new User($connection, $postedBy);
    ?>
    <div class="comments-section">
        <a href="<?php echo $postedBy;?>" target="_parent">
            <img class="comments_profile_picture" title="<?php echo $postedBy;?>" src="<?php echo $user->profilePicture; ?>"></img>
        </a>
        <a href="<?php echo $postedBy;?>" target="_parent"><b><?php echo $user->name; ?></b></a>
        &nbsp;&nbsp;&nbsp; <?php echo $dateMessage . "<br>" . $CommentBody;?>
        <hr>
    </div>

    <?php
            }
        } else {
            echo '<center><br><br>No comments to show!</center>';
        }
    ?>
</body>
</html>

<?php
include('includes/header.php');
include('includes/classes/User.php');
include('includes/classes/Post.php');

if (isset($_POST['post'])) {
    $post = new Post($connection, $loginUsername);
    $post->submitPost($_POST['post-text'], 'none');

    header('Location: index.php');
}
?>
        <div class="user-details column">
            <a href="<?php echo $loginUsername; ?>">
                <img src="<?php echo $user['profile_pic']; ?>">
            </a>
            <div class="user-personal-details">
                <a href="<?php echo $loginUsername; ?>">
                    <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                </a>
                <br>
                <?php echo 'Posts: ' . $user['num_posts'] . '<br>';
                    echo 'Likes: ' . $user['num_likes']; ?>
            </div>
        </div>
        <div class="main-column column">
            <form class="post-form" action="index.php" method="POST">
                <textarea name="post-text" class="post-text" placeholder="Got something to say?"></textarea>
                <input type="submit" name="post" id="post_button" value="Post">
                <hr>
            </form>
            <div class="posts_area"></div>
            <img id="loading" src="assets/images/icons/loading.gif">
        </div>
        <script>
            const loginUsername = '<?php echo $loginUsername; ?>';

            $(document).ready(function() {
                $('#loading').show();

                $.ajax({
                    url: 'includes/handlers/ajaxLoadPosts.php',
                    type: 'POST',
                    data: 'page=1&loginUsername=' + loginUsername,
                    cache:false,
                    success: function(data) {
                        // $('#loading').hide();
                        $('.posts_area').html(data);
                    },
                });

                $(window).scroll(function() {
                    const height = $('.posts_area').height();
                    const scrollTop = $(this).scrollTop();
                    const page = $('.posts_area').find('.nextPage').val();
                    const noMorePosts = $('.posts_area').find('.noMorePosts').val();

                    if ((document.body.scrollHeight === document.body.scrollTop + window.innerHeight) && noMorePosts === 'false') {
                        $('#loading').show();

                        const ajaxRequest = $.ajax({
                            url: 'includes/handlers/ajaxLoadPosts.php',
                            type: 'POST',
                            data: 'page=' + page + '&loginUsername=' + loginUsername,
                            cache:false,
                            success: function(response) {
                                $('.posts_area').find('.nextPage').remove();
                                $('.posts_area').find('.noMorePosts').remove();

                                $('#loading').hide();
                                $('.posts_area').append(response);
                            },
                        });
                    }

                    return false;
                });
            });
        </script>
    </div>
</body>
</html>
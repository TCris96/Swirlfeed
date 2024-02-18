<?php 
require_once(__DIR__ . '/../utils/DateTimeUtility.php');

class Post {
    private $user;
    private $connection;

    public function __construct($connection, $username) {
        $this->connection = $connection;
        $this->user = new User($connection, $username);
    }

    public function submitPost($body, $userTo) {
        $body = strip_tags($body); //removes html tags
        $body = mysqli_real_escape_string($this->connection, $body);

        //Allow new lines on the post body
        $body = str_replace('\r\n', '\n', $body);
        $body = nl2br($body);
        $checkEmpty = preg_replace('/\s+/', '', $body);//Delete all spaces

        if ($checkEmpty != '') {
            $dateAdded = date('Y-m-d H:i:s');
            $addedBy = $this->user->getUsername();

            //If user in on own profile, user_to is none
            if ($userTo === $addedBy) {
                $userTo = 'none';
            }
        }

        //Save post
        $this->save($body, $addedBy, $userTo, $dateAdded);

        //Insert notification

        //Update post count for user
        $this->updatePostsNo();
    }

    public function loadPostsFriends($data, $limit) {
        $page = $data['page'];
        $loginUsername = $this->user->getUsername();
        $start = 0;

        if ($page > 1) {
            $start = ($page - 1) * $limit;
        }

        $postsHtml = '';

        $dataQuery = mysqli_query($this->connection, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

        if (mysqli_num_rows($dataQuery) > 0) {
            $iterationNo = 0;//number of results checked(not necesserly posted)
            $count = 1;

            while ($row = mysqli_fetch_array($dataQuery)) {
                $id = $row['id'];
                $body = $row['body'];
                $dateTime = $row['date_added'];
                $addedBy = $row['added_by'];

                if ($row['user_to'] === 'none') {
                    $userTo = '';
                } else {
                    $user = new User($this->connection, $row['user_to']);
                    $name = $user->name;
                    $userTo = "to <a href='" . $row['user_to'] . "'>" . $name . "</a>";
                }

                //If the user who posted the post is closed we don't want to display the post
                $addedByUser = new User($this->connection, $addedBy);
                $userFullName = $addedByUser->name;
                $profilePicture = $addedByUser->profilePicture;

                if ($addedByUser->isClosed()) {
                    continue;
                }

                if ($this->user->isFriend($addedBy)) {

                    if ($iterationNo++ < $start) {
                        continue;
                    }

                    //Once 10 posts are loaded break the iteration
                    if ($count > $limit) {
                        break;
                    } else {
                        $count++;
                    }
                    ?>

                    <script>
                        function toggle<?php echo $id; ?>() {
                            const target = $(event.target);

                            if (target.is('a')) {
                                return;
                            }

                            const commentElement = document.getElementById('toggleComment<?php echo $id; ?>');

                            commentElement.style.display = commentElement.style.display === 'block' ? 'none' : 'block';
                        }
                    </script>

                    <?php
                    $commentsQuery = mysqli_query($this->connection, "SELECT * FROM comments WHERE post_id='$id'");
                    $commentsNo = mysqli_num_rows($commentsQuery);

                    $timeMessage = DateTimeUtility::getDateTimeMessage($dateTime);

                    $postsHtml .= "
                        <div class='status_post' onClick='javascript:toggle$id()'>
                            <div class='post_profile_picture'>
                                <img src='$profilePicture' width='50'>
                            </div>
                            <div class='posted_by' style='color:#ACACAC;'>
                                <a href='$addedBy'>$userFullName</a>
                                $userTo &nbsp;&nbsp;&nbsp;$timeMessage
                            </div>
                            <div id='post_body'>
                                $body
                            </div>
                            <br>
                            <br>
                            <br>
                            <div class='newfeedPostOptions'>
                            Comments($commentsNo)&nbsp&nbsp&nbsp
                            <iframe src='likes.php?post_id=$id' scrolling='no'></iframe>
                            </div>
                            <div class='post_comment' id='toggleComment$id' style='display:none;'>
                                <iframe src='comments.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
                            </div>
                        </div>
                        <hr>
                    ";
                }
            }

            if ($count > $limit) {
                $postsHtml .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
                            <input type='hidden' class='noMorePosts' value='false'>";
            } else {
                $postsHtml .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: center;'>No more posts to show!</p>";
            }
    }

        echo $postsHtml;
    }

    public function save($body, $addedBy, $userTo, $dateAdded, $userClosed = 'no', $deleted = 'no', $likes = 0) {
        mysqli_query($this->connection,
            "INSERT INTO posts VALUES('', '$body', '$addedBy', '$userTo', '$dateAdded', '$userClosed', '$deleted', '$likes')");
        
        $returnedId = mysqli_insert_id($this->connection);

        return $returnedId;

    }

    public function updatePostsNo() {
        $addedBy = $this->user->getUsername();
        $postsNo = $this->user->getPostsNo();
        $postsNo++;
        $updateQuery = mysqli_query($this->connection, "UPDATE users SET num_posts='$postsNo' WHERE username='$addedBy'");
    }
}
?>
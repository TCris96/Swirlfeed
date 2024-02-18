<?php
include('../../config/config.php');
include('../classes/User.php');
include('../classes/Post.php');

$limit = 10;

$posts = new Post($connection, $_REQUEST['loginUsername']);
$posts->loadPostsFriends($_REQUEST, $limit);
?>
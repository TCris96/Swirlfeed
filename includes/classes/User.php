<?php 
class User {
    public $name;
    public $firstName;
    public $lastName;
    public $profilePicture;
    public $isClosed;
    private $connection;

    public function __construct($connection, $user) {
        $this->connection = $connection;
        $userDetailsQuery = mysqli_query($connection, "SELECT * FROM users WHERE username='$user'");
        $row = mysqli_fetch_array($userDetailsQuery);
        $this->name = $row['first_name'] . ' ' . $row['last_name'];
        $this->firstName = $row['first_name'];
        $this->lastName = $row['last_name'];
        $this->profilePicture = $row['profile_pic'];
        $this->isClosed = $row['user_closed'];
        $this->username = $row['username'];
        $this->friendArray = $row['friend_array'];
        $this->likesNumber = $row['num_likes'];
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPostsNo() {
        $username = $this->username;
        $query = mysqli_query($this->connection, "SELECT num_posts FROM users WHERE username='$username'");
        $row = mysqli_fetch_array($query);

        return $row['num_posts'];
    }

    public function getFirstLastName() {
        return $this->name;
    }

    public function isClosed() {
        return $this->isClosed === 'yes';
    }

    public function isFriend($userToCheck) {
        $formattedUser = ',' . $userToCheck . ',';

        return strstr($this->friendArray, $formattedUser) || $this->username === $userToCheck;
    }
}
?>
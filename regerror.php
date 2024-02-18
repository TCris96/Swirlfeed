<?php
class RegError {
    const INCORRECT_FIRST_NAME = 'Your first name must be between 2 and 25 characters!';
    const INCORRECT_LAST_NAME = 'Your last name must be between 2 and 25 characters!';
    const EMAIL_EXISTS = 'Email already exists';
    const EMAILS_NOT_MATCHING = 'Emails do not match';
    const INCORRECT_EMAIL = 'Invalid email format!';
    const PASSWORD_NOT_MATCHING = 'Passwords do not match!';
    const INCORRECT_PASSWORD = 'Password must be between 5 and 30 characters!';
    const INCORRECT_PASSWORD_FORMAT = 'Password must contains only english characters and numbers!';
    const LOGIN_FAILED = 'Incorrect email address or password. Please try again!';

    public static $error_array = [];

    public function __construct($name, $message) {
        $this->name = $name;
        $this->message = $message;

        self::$error_array[] = $this;
    }

    public function getName() {
        return $this->name;
    }

    public function getMessage() {
        return $this->message;
    }
    
    public static function containsError($name) {
        if (count(self::$error_array) <= 0) {
            return false;
        }

        foreach(self::$error_array as $error) {
            if ($error->name == $name) {
                return true;
            }
        }

        return false;
    }

    public static function getErrorArray() {
        return self::$error_array;
    }
    
    public static function getError($name) {
        foreach(self::$error_array as $error) {
            if ($error->name == $name) {
                return $error;
            }
        }
    }
}
?>
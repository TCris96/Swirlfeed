<?php
class Utils {
    public static function validateName($name) {
        if (strlen($name) > 25 || strlen($name) < 2) {
            return true;
        }

        return false;
    }
    
    public static function validatePassword($password) {
        if (strlen($password) > 30 || strlen($password) < 5) {
            return true;
        }

        return false;
    }
    
    public static function removeTags($value) {
        return strip_tags($value);
    }

    public static function sanitazeValue($value) {
        $sanitazedValue = str_replace(' ', '', $value);

        return ucfirst(strtolower($sanitazedValue));
    }

    public static function clearSessionFormVariables() {
        $_SESSION['reg_fname'] = '';
        $_SESSION['reg_lname'] = '';
        $_SESSION['reg_email'] = '';
        $_SESSION['reg_email2'] = '';
    }
}
?>
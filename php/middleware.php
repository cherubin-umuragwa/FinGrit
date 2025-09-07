<?php
require_once 'auth.php';

class Middleware {
    public static function authRequired() {
        $auth = new Auth();
        if (!$auth->isLoggedIn()) {
            header("Location: login.php");
            exit();
        }
    }

    public static function guestOnly() {
        $auth = new Auth();
        if ($auth->isLoggedIn()) {
            header("Location: dashboard.php");
            exit();
        }
    }

    public static function getCurrentUser() {
        $auth = new Auth();
        return $auth->getCurrentUser();
    }
}
?>
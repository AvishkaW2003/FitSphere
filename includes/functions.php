<?php
require_once __DIR__ . '/../session.php';

use FitSphere\Core\Session;

require_once __DIR__ . '/../models.php';

class Auth {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
        Session::start();
    }

    public function login($email, $password) {
    $user = $this->userModel->findByEmail($email);
    if (!$user) {
        return false; // No such user
    }

    // Check status
    if ($user['status'] != 1) {
        // Not active
        Session::set('error', 'Account is inactive. Please contact admin.');
        return false;
    }

    if (password_verify($password, $user['password'])) {
        Session::set('user', $user);
        return true;
    }
    return false;
    }


    public static function check() {
        return isset($_SESSION['user']);
    }

    public static function user() {
        return $_SESSION['user'] ?? null;
    }
    public static function logout() {
        Session::destroy();
    }
}

<?php
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../models.php';

use FitSphere\Core\Session;

class Auth {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
        Session::start();
    }

    public function login($email, $password) {
        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            Session::set('error', 'Invalid email or password.');
            return false;
        }

        // Check DB status (Active, Suspended, Blocked)
        if ($user['status'] !== 'Active') {
            Session::set('error', 'Your account is '.$user['status'].'. Contact admin.');
            return false;
        }

        if (password_verify($password, $user['password'])) {
            Session::set('user', $user);
            return true;
        }

        Session::set('error', 'Invalid credentials.');
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

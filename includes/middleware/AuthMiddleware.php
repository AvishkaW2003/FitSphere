<?php
require_once __DIR__ . '/../functions.php';
use FitSphere\Core\Session;

Session::start();

class AuthMiddleware {

    // Check if logged in
    public static function requireLogin() {
        if (!Auth::check()) {
            header("Location: /FitSphere/login.php");
            exit;
        }
    }

    // Allow only specific role (admin/user/guest)
    public static function requireRole($role) {
        self::requireLogin();

        $user = Auth::user();

        if ($user['role'] !== $role) {
            header("Location: /FitSphere/login.php");
            exit;
        }
    }

    // Allow multiple roles
    public static function requireAnyRole(array $roles) {
        self::requireLogin();

        $user = Auth::user();

        if (!in_array($user['role'], $roles)) {
            header("Location: /FitSphere/login.php");
            exit;
        }
    }
}

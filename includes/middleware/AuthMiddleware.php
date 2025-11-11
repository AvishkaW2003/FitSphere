<?php
require_once __DIR__ . '/../functions.php';
use FitSphere\Core\Session;
Session::start();

class AuthMiddleware
{
    //Check if user is logged in, otherwise redirect to login
    
    public static function requireLogin()
    {
        if (!Auth::check()) {
            header("Location: /FitSphere/login.php");
            exit;
        }
    }

    
      //Restrict access by role
    
    public static function requireRole($role)
    {
        self::requireLogin();

        $user = Auth::user();

        // if role doesn’t match, just redirect – don’t destroy session
        if ($user['role'] !== $role) {
            header("Location: /FitSphere/login.php");
            exit;
        }
    }

    
     // Allow several roles
     
    public static function requireAnyRole(array $roles)
    {
        self::requireLogin();

        $user = Auth::user();

        if (!in_array($user['role'], $roles)) {
            header("Location: /FitSphere/login.php");
            exit;
        }
    }
}

<?php
namespace FitSphere\Core;

class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            // Ensure session works across all folders
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/', // very important!
                'domain' => '',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public static function destroy() {
        $_SESSION = [];
        if (session_id() !== '' || isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();
    }
}

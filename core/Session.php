<?php

namespace App\Core;

/**
 * Session, Flash Message, and CSRF Protection Helper
 */
class Session {
    // Start session if not already started
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Set a session variable
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }

    // Get a session variable
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    // Remove a session variable
    public static function remove($key) {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    // Destroy session
    public static function destroy() {
        self::start();
        session_destroy();
        $_SESSION = [];
    }

    // Set flash message (persists only for the next request)
    public static function setFlash($key, $message) {
        self::start();
        $_SESSION['flash'][$key] = $message;
    }

    // Get flash message and delete it immediately
    public static function getFlash($key) {
        self::start();
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    // Check if flash message exists
    public static function hasFlash($key) {
        self::start();
        return isset($_SESSION['flash'][$key]);
    }

    // CSRF protection: Generate dynamic secure token
    public static function generateCsrfToken() {
        self::start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // CSRF protection: Validate received token
    public static function validateCsrfToken($token) {
        self::start();
        $storedToken = self::get('csrf_token');
        if ($storedToken && hash_equals($storedToken, $token)) {
            return true;
        }
        return false;
    }

    // Check if user is logged in
    public static function isLoggedIn() {
        return self::get('user_id') !== null;
    }

    // Check user role
    public static function hasRole($role) {
        return self::get('user_role') === $role;
    }
}

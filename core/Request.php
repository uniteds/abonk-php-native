<?php

namespace App\Core;

/**
 * HTTP Request and Input Sanitization Helper
 */
class Request {
    // Check request method (GET, POST, etc.)
    public static function method() {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public static function isPost() {
        return self::method() === 'POST';
    }

    public static function isGet() {
        return self::method() === 'GET';
    }

    // Sanitize input to prevent XSS (Cross-Site Scripting)
    public static function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
        } else {
            // Decode any existing entities first to prevent buildup, then re-encode safely
            $data = trim($data);
            $decoded = html_entity_decode($data, ENT_QUOTES, 'UTF-8');
            $data = htmlspecialchars($decoded, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }

    // Get value from $_POST
    public static function post($key = null, $default = null) {
        if ($key === null) {
            return self::sanitize($_POST);
        }
        return isset($_POST[$key]) ? self::sanitize($_POST[$key]) : $default;
    }

    // Get value from $_GET
    public static function get($key = null, $default = null) {
        if ($key === null) {
            return self::sanitize($_GET);
        }
        return isset($_GET[$key]) ? self::sanitize($_GET[$key]) : $default;
    }

    // Get file upload info from $_FILES
    public static function file($key) {
        return $_FILES[$key] ?? null;
    }

    // Sanitize output for safe display in views (XSS Prevention)
    public static function escape($string) {
        if ($string === null) return '';
        $decoded = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
        return htmlspecialchars($decoded, ENT_QUOTES, 'UTF-8');
    }
}

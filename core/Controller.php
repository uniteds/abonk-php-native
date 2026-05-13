<?php

namespace App\Core;

/**
 * Base Controller class
 */
abstract class Controller {
    // Render a view file with data array
    protected function view($view, $data = []) {
        // Extract variables to be accessible in the view
        extract($data);
        
        // Build the file path
        // e.g., admin/posts/index -> app/Views/admin/posts/index.php
        $file = __DIR__ . '/../app/Views/' . $view . '.php';
        
        if (file_exists($file)) {
            require_once $file;
        } else {
            die("View file not found: " . $view);
        }
    }

    // Redirect to a specific path
    protected function redirect($path) {
        $url = BASE_URL . '/' . ltrim($path, '/');
        header("Location: " . $url);
        exit;
    }
}

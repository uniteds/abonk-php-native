<?php

namespace App\Core;

/**
 * Custom Front Controller Router using Regular Expressions
 */
class Router {
    private $routes = [];

    // Register a GET route
    public function get($route, $handler) {
        $this->addRoute('GET', $route, $handler);
    }

    // Register a POST route
    public function post($route, $handler) {
        $this->addRoute('POST', $route, $handler);
    }

    // Add route helper
    private function addRoute($method, $route, $handler) {
        // Convert route wildcard placeholders like {id} or {slug} to regex capture groups
        // /posts/edit/{id} -> ^/posts/edit/([^/]+)$
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
        $pattern = '#^' . trim($pattern, '/') . '$#';
        
        $this->routes[] = [
            'method'  => $method,
            'pattern' => $pattern,
            'handler' => $handler,
            'route'   => $route
        ];
    }

    // Match the current request and dispatch it
    public function dispatch() {
        $method = Request::method();
        
        // Get path relative to the dynamic base URL
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Strip query strings: /some/path?param=1 -> /some/path
        $path = parse_url($uri, PHP_URL_PATH);
        
        // If app is in a subdirectory, strip that subdirectory from the path
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $dir = dirname($scriptName);
        if ($dir !== '/' && $dir !== '\\') {
            $path = preg_replace('#^' . preg_quote($dir, '#') . '#', '', $path);
        }
        
        // Robust fallback: Strip explicit 'index.php' prefix if present
        $path = preg_replace('#^/?index\.php/?#i', '/', $path);
        
        // Trim slashes from path for easier matching
        $path = trim($path, '/');
        if ($path === '') {
            $path = '';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $path, $matches)) {
                // Remove first element (full match) from matches array to keep only captured parameters
                array_shift($matches);
                
                // Parse handler (e.g. "HomeController@index")
                $parts = explode('@', $route['handler']);
                $controllerName = 'App\\Controllers\\' . $parts[0];
                $methodName = $parts[1];
                
                if (class_exists($controllerName)) {
                    $controllerInstance = new $controllerName();
                    if (method_exists($controllerInstance, $methodName)) {
                        // Call method and pass the route parameters
                        call_user_func_array([$controllerInstance, $methodName], $matches);
                        return;
                    }
                }
                
                $this->sendError(500, "Method '{$methodName}' not found in controller '{$controllerName}'");
                return;
            }
        }

        // No route found
        $this->sendError(404, "Page Not Found");
    }

    // Standard error renderer
    private function sendError($code, $message) {
        http_response_code($code);
        
        // Clean and simple error design using the user's custom color scheme!
        echo "<!DOCTYPE html>
        <html lang='id'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Error {$code} - CMS</title>
            <link href='https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap' rel='stylesheet'>
            <style>
                body {
                    margin: 0;
                    padding: 0;
                    background-color: #FFF8E7;
                    color: #051f14;
                    font-family: 'Plus Jakarta Sans', sans-serif;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    text-align: center;
                }
                .error-container {
                    padding: 40px;
                    border: 1px solid rgba(5, 31, 20, 0.08);
                    background: #ffffff;
                    border-radius: 16px;
                    box-shadow: 0 10px 30px rgba(5, 31, 20, 0.03);
                    max-width: 500px;
                }
                h1 {
                    font-size: 72px;
                    margin: 0 0 10px 0;
                    color: #00A86B;
                    font-weight: 800;
                }
                h2 {
                    font-size: 24px;
                    margin: 0 0 20px 0;
                    font-weight: 600;
                }
                p {
                    color: rgba(5, 31, 20, 0.7);
                    font-size: 16px;
                    line-height: 1.6;
                    margin-bottom: 30px;
                }
                a {
                    display: inline-block;
                    padding: 12px 24px;
                    background-color: #00A86B;
                    color: #FFF8E7;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                }
                a:hover {
                    opacity: 0.9;
                    transform: translateY(-2px);
                }
            </style>
        </head>
        <body>
            <div class='error-container'>
                <h1>{$code}</h1>
                <h2>{$message}</h2>
                <p>Maaf, halaman yang Anda cari tidak dapat ditemukan atau terjadi masalah pada server kami.</p>
                <a href='" . BASE_URL . "'>Kembali ke Beranda</a>
            </div>
        </body>
        </html>";
        exit;
    }
}

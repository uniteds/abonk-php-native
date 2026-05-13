<?php
/**
 * Custom PSR-4-like Autoloader for CMS
 */

spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'App\\';

    // Base directory for the namespace prefix
    $base_dir = __DIR__ . '/../';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    
    // We map:
    // App\Core\Database -> core/Database.php
    // App\Controllers\HomeController -> app/Controllers/HomeController.php
    // App\Models\Post -> app/Models/Post.php
    
    $parts = explode('\\', $relative_class);
    
    // If first part is "Core", map to core/ folder
    if ($parts[0] === 'Core') {
        array_shift($parts); // remove "Core"
        $file = $base_dir . 'core/' . implode('/', $parts) . '.php';
    } else {
        // Map App\Controllers\... to app/Controllers/...
        // Map App\Models\... to app/Models/...
        $file = $base_dir . 'app/' . implode('/', $parts) . '.php';
    }

    // If the file exists, require it
    if (file_exists($file)) {
        require_once $file;
    }
});

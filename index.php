<?php
/**
 * Abonk CMS - Front Controller & Entry Point
 * Handles dynamic routing, autoloading, and database setup detection.
 */

// 1. Load Configurations and Autoloader
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/autoload.php';

// 2. Start Session Manager
use App\Core\Session;
Session::start();

// 3. Database Check: If database doesn't exist, redirect to installer automatically
try {
    $db = \App\Core\Database::getInstance()->getConnection();
    // Try to query settings to see if tables exist and populated
    $stmt = $db->query("SHOW TABLES LIKE 'settings'");
    if ($stmt->rowCount() === 0) {
        // Tables not created yet, redirect to install.php
        header("Location: install.php");
        exit;
    }

    // Auto-migration check: Ensure profile columns exist in users table
    try { $db->exec("ALTER TABLE `users` ADD COLUMN `name` VARCHAR(100) DEFAULT NULL"); } catch (\Exception $e) {}
    try { $db->exec("ALTER TABLE `users` ADD COLUMN `profile_photo` VARCHAR(255) DEFAULT NULL"); } catch (\Exception $e) {}
    try { $db->exec("ALTER TABLE `users` ADD COLUMN `bio` TEXT DEFAULT NULL"); } catch (\Exception $e) {}
    try { $db->exec("ALTER TABLE `users` ADD COLUMN `social_facebook` VARCHAR(255) DEFAULT NULL"); } catch (\Exception $e) {}
    try { $db->exec("ALTER TABLE `users` ADD COLUMN `social_twitter` VARCHAR(255) DEFAULT NULL"); } catch (\Exception $e) {}
    try { $db->exec("ALTER TABLE `users` ADD COLUMN `social_instagram` VARCHAR(255) DEFAULT NULL"); } catch (\Exception $e) {}
    try { $db->exec("ALTER TABLE `users` ADD COLUMN `social_linkedin` VARCHAR(255) DEFAULT NULL"); } catch (\Exception $e) {}
    try { $db->exec("ALTER TABLE `users` ADD COLUMN `social_github` VARCHAR(255) DEFAULT NULL"); } catch (\Exception $e) {}
    try { $db->exec("ALTER TABLE `posts` ADD COLUMN `is_featured` TINYINT(1) DEFAULT 0"); } catch (\Exception $e) {}
    try { $db->exec("ALTER TABLE `posts` ADD COLUMN `tags` VARCHAR(255) DEFAULT NULL"); } catch (\Exception $e) {}
    try { $db->exec("ALTER TABLE `posts` ADD COLUMN `views_count` INT DEFAULT 0"); } catch (\Exception $e) {}
    try { $db->exec("ALTER TABLE `pages` ADD COLUMN `show_in_topbar` TINYINT(1) DEFAULT 0"); } catch (\Exception $e) {}

    // Auto-migration check: Ensure pages table exists
    $db->exec("CREATE TABLE IF NOT EXISTS `pages` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) NOT NULL UNIQUE,
        `content` TEXT NOT NULL,
        `status` ENUM('draft', 'published') DEFAULT 'draft',
        `show_in_topbar` TINYINT(1) DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

} catch (\Exception $e) {
    // Database connection failed or doesn't exist, go to installer
    header("Location: install.php");
    exit;
}

// 4. Instantiate Router
use App\Core\Router;
$router = new Router();

// 5. REGISTER ROUTES

// --- Frontend Public Routes ---
$router->get('', 'HomeController@index');
$router->get('sitemap.xml', 'HomeController@sitemap');
$router->get('post/{slug}', 'HomeController@post');
$router->get('category/{slug}', 'HomeController@category');
$router->get('page/{slug}', 'HomeController@page');

// --- Administrative Authentication Routes ---
$router->get('admin/login', 'AuthController@login');
$router->post('admin/login', 'AuthController@login');
$router->get('admin/logout', 'AuthController@logout');

// --- Admin Panel Main Dashboard & Settings ---
$router->get('admin', 'AdminController@index');
$router->get('admin/profile', 'AdminController@profile');
$router->get('admin/profile/edit', 'AdminController@profileEdit');
$router->post('admin/profile/edit', 'AdminController@profileEdit');
$router->get('admin/settings', 'AdminController@settings');
$router->post('admin/settings', 'AdminController@settings');

// --- Admin CRUD: Posts (Articles) ---
$router->get('admin/posts', 'PostController@index');
$router->get('admin/posts/create', 'PostController@create');
$router->post('admin/posts/create', 'PostController@create');
$router->get('admin/posts/edit/{id}', 'PostController@edit');
$router->post('admin/posts/edit/{id}', 'PostController@edit');
$router->get('admin/posts/delete/{id}', 'PostController@delete');

// --- Admin CRUD: Pages (Static Pages) ---
$router->get('admin/pages', 'PageController@index');
$router->get('admin/pages/create', 'PageController@create');
$router->post('admin/pages/create', 'PageController@create');
$router->get('admin/pages/edit/{id}', 'PageController@edit');
$router->post('admin/pages/edit/{id}', 'PageController@edit');
$router->get('admin/pages/delete/{id}', 'PageController@delete');

// --- Admin CRUD: Categories ---
$router->get('admin/categories', 'CategoryController@index');
$router->get('admin/categories/create', 'CategoryController@create');
$router->post('admin/categories/create', 'CategoryController@create');
$router->get('admin/categories/edit/{id}', 'CategoryController@edit');
$router->post('admin/categories/edit/{id}', 'CategoryController@edit');
$router->get('admin/categories/delete/{id}', 'CategoryController@delete');

// --- Admin CRUD: Users ---
$router->get('admin/users', 'UserController@index');
$router->get('admin/users/create', 'UserController@create');
$router->post('admin/users/create', 'UserController@create');
$router->get('admin/users/edit/{id}', 'UserController@edit');
$router->post('admin/users/edit/{id}', 'UserController@edit');
$router->get('admin/users/delete/{id}', 'UserController@delete');

// --- Admin CRUD: Navigation Menus ---
$router->get('admin/menus', 'NavigationMenuController@index');
$router->get('admin/menus/create', 'NavigationMenuController@create');
$router->post('admin/menus/create', 'NavigationMenuController@create');
$router->get('admin/menus/edit/{id}', 'NavigationMenuController@edit');
$router->post('admin/menus/edit/{id}', 'NavigationMenuController@edit');
$router->get('admin/menus/delete/{id}', 'NavigationMenuController@delete');

// 6. DISPATCH REQUESTS
$router->dispatch();

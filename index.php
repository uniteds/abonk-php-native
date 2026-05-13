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
$router->get('post/{slug}', 'HomeController@post');
$router->get('category/{slug}', 'HomeController@category');

// --- Administrative Authentication Routes ---
$router->get('admin/login', 'AuthController@login');
$router->post('admin/login', 'AuthController@login');
$router->get('admin/logout', 'AuthController@logout');

// --- Admin Panel Main Dashboard & Settings ---
$router->get('admin', 'AdminController@index');
$router->get('admin/settings', 'AdminController@settings');
$router->post('admin/settings', 'AdminController@settings');

// --- Admin CRUD: Posts (Articles) ---
$router->get('admin/posts', 'PostController@index');
$router->get('admin/posts/create', 'PostController@create');
$router->post('admin/posts/create', 'PostController@create');
$router->get('admin/posts/edit/{id}', 'PostController@edit');
$router->post('admin/posts/edit/{id}', 'PostController@edit');
$router->get('admin/posts/delete/{id}', 'PostController@delete');

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

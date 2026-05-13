<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Setting;
use App\Models\NavigationMenu;

/**
 * Home Controller (Frontend)
 */
class HomeController extends Controller {
    private $postModel;
    private $categoryModel;
    private $settingModel;
    private $menuModel;
    private $globalSettings;
    private $globalMenus;

    public function __construct() {
        $this->postModel = new Post();
        $this->categoryModel = new Category();
        $this->settingModel = new Setting();
        $this->menuModel = new NavigationMenu();
        
        // Fetch all settings dynamically to make them available in all frontend views
        $this->globalSettings = $this->settingModel->getAll();
        
        // Fetch custom navigation menus
        $this->globalMenus = $this->menuModel->getAll();
    }

    // Homepage with search, pagination, and sidebars
    public function index() {
        $search = Request::get('q', '');
        $page = (int) Request::get('page', 1);
        if ($page < 1) $page = 1;

        $limit = 8; // Posts per page
        $offset = ($page - 1) * $limit;

        // Get matching published posts
        $posts = $this->postModel->getPublished($limit, $offset, $search);
        
        // Count matching posts for pagination
        $totalPosts = $this->postModel->countPublished($search);
        $totalPages = ceil($totalPosts / $limit);

        // Sidebar widgets data
        $categories = $this->categoryModel->getAllWithCount();
        $recentPosts = $this->postModel->getRecent(5);

        $this->view('frontend/home', [
            'settings'    => $this->globalSettings,
            'menus'       => $this->globalMenus,
            'posts'       => $posts,
            'categories'  => $categories,
            'recentPosts' => $recentPosts,
            'search'      => $search,
            'currentPage' => $page,
            'totalPages'  => $totalPages
        ]);
    }

    // Article Detail Page
    public function post($slug) {
        $post = $this->postModel->findBySlug($slug);
        
        if (!$post) {
            // Render 404
            http_response_code(404);
            $this->view('frontend/404', [
                'settings' => $this->globalSettings,
                'menus'    => $this->globalMenus
            ]);
            return;
        }

        // Sidebar widgets data
        $categories = $this->categoryModel->getAllWithCount();
        $recentPosts = $this->postModel->getRecent(5);

        $this->view('frontend/post', [
            'settings'    => $this->globalSettings,
            'menus'       => $this->globalMenus,
            'post'        => $post,
            'categories'  => $categories,
            'recentPosts' => $recentPosts
        ]);
    }

    // Category Filter Page
    public function category($slug) {
        $category = $this->categoryModel->findBySlug($slug);
        
        if (!$category) {
            http_response_code(404);
            $this->view('frontend/404', [
                'settings' => $this->globalSettings,
                'menus'    => $this->globalMenus
            ]);
            return;
        }

        $search = Request::get('q', '');
        $page = (int) Request::get('page', 1);
        if ($page < 1) $page = 1;

        $limit = 8;
        $offset = ($page - 1) * $limit;

        // Get matching posts for this specific category
        $posts = $this->postModel->getPublished($limit, $offset, $search, $category['id']);
        
        // Count matching posts in this category
        $totalPosts = $this->postModel->countPublished($search, $category['id']);
        $totalPages = ceil($totalPosts / $limit);

        // Sidebar widgets data
        $categories = $this->categoryModel->getAllWithCount();
        $recentPosts = $this->postModel->getRecent(5);

        $this->view('frontend/category', [
            'settings'     => $this->globalSettings,
            'menus'        => $this->globalMenus,
            'category'     => $category,
            'posts'        => $posts,
            'categories'   => $categories,
            'recentPosts'  => $recentPosts,
            'search'       => $search,
            'currentPage'  => $page,
            'totalPages'   => $totalPages
        ]);
    }
}

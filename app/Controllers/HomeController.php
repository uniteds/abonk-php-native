<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Setting;
use App\Models\NavigationMenu;
use App\Models\Page;
use App\Models\User;

/**
 * Home Controller (Frontend)
 */
class HomeController extends Controller {
    private $postModel;
    private $categoryModel;
    private $settingModel;
    private $menuModel;
    private $pageModel;
    private $userModel;
    private $globalSettings;
    private $globalMenus;

    public function __construct() {
        $this->postModel = new Post();
        $this->categoryModel = new Category();
        $this->settingModel = new Setting();
        $this->menuModel = new NavigationMenu();
        $this->pageModel = new Page();
        $this->userModel = new User();
        
        // Fetch all settings dynamically to make them available in all frontend views
        $this->globalSettings = $this->settingModel->getAll();
        
        // Fetch custom navigation menus
        $this->globalMenus = $this->menuModel->getAll();
    }

    // Homepage with search, pagination, and sidebars
    public function index() {
        $search = Request::get('q', '');
        $tagFilter = Request::get('tag', '');
        $page = (int) Request::get('page', 1);
        if ($page < 1) $page = 1;

        $limit = 8; // Posts per page
        $offset = ($page - 1) * $limit;

        // Get matching published posts
        $posts = $this->postModel->getPublished($limit, $offset, $search, null, $tagFilter);
        
        // Count matching posts for pagination
        $totalPosts = $this->postModel->countPublished($search, null, $tagFilter);
        $totalPages = ceil($totalPosts / $limit);

        // Sidebar widgets data
        $categories = $this->categoryModel->getAllWithCount();
        $recentPosts = $this->postModel->getRecent(5);
        $featuredPosts = $this->postModel->getFeatured(6);
        $adminUser = $this->userModel->findByUsername('admin');
        $popularTags = $this->postModel->getPopularTags(6);

        $canonicalUrl = BASE_URL;
        if ($page > 1) {
            $canonicalUrl .= '?page=' . $page;
        }
        if (!empty($tagFilter)) {
            $canonicalUrl .= (!strpos($canonicalUrl, '?') ? '?' : '&') . 'tag=' . urlencode($tagFilter);
        }

        $this->view('frontend/home', [
            'canonicalUrl'  => $canonicalUrl,
            'settings'      => $this->globalSettings,
            'menus'         => $this->globalMenus,
            'posts'         => $posts,
            'categories'    => $categories,
            'recentPosts'   => $recentPosts,
            'featuredPosts' => $featuredPosts,
            'adminUser'     => $adminUser,
            'popularTags'   => $popularTags,
            'search'        => $search,
            'tagFilter'     => $tagFilter,
            'currentPage'   => $page,
            'totalPages'    => $totalPages
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

        $metaDescription = trim(substr(strip_tags($post['content']), 0, 160));
        $ogImage = !empty($post['image']) ? BASE_URL . '/assets/uploads/' . $post['image'] : BASE_URL . '/assets/uploads/kotapadang.jpeg';
        $ogType = 'article';
        $canonicalUrl = BASE_URL . '/post/' . urlencode($post['slug']);

        $this->view('frontend/post', [
            'title'           => $post['title'],
            'metaDescription' => $metaDescription,
            'ogImage'         => $ogImage,
            'ogType'          => $ogType,
            'canonicalUrl'    => $canonicalUrl,
            'settings'        => $this->globalSettings,
            'menus'           => $this->globalMenus,
            'post'            => $post,
            'categories'      => $categories,
            'recentPosts'     => $recentPosts
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
        $adminUser = $this->userModel->findByUsername('admin');

        $metaDescription = "Kumpulan pos artikel dalam kategori " . $category['name'] . ".";
        $ogImage = BASE_URL . '/assets/uploads/kotapadang.jpeg';
        $ogType = 'website';
        $canonicalUrl = BASE_URL . '/category/' . urlencode($category['slug']);

        $this->view('frontend/category', [
            'title'           => 'Kategori: ' . $category['name'],
            'metaDescription' => $metaDescription,
            'ogImage'         => $ogImage,
            'ogType'          => $ogType,
            'canonicalUrl'    => $canonicalUrl,
            'settings'        => $this->globalSettings,
            'menus'           => $this->globalMenus,
            'category'        => $category,
            'posts'           => $posts,
            'categories'      => $categories,
            'recentPosts'     => $recentPosts,
            'adminUser'       => $adminUser,
            'search'          => $search,
            'currentPage'     => $page,
            'totalPages'      => $totalPages
        ]);
    }

    // Static Page View
    public function page($slug) {
        $page = $this->pageModel->findBySlug($slug, true);
        
        if (!$page) {
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

        $metaDescription = trim(substr(strip_tags($page['content']), 0, 160));
        $ogImage = BASE_URL . '/assets/uploads/kotapadang.jpeg';
        $ogType = 'article';
        $canonicalUrl = BASE_URL . '/page/' . urlencode($page['slug']);

        $this->view('frontend/page', [
            'title'           => $page['title'],
            'metaDescription' => $metaDescription,
            'ogImage'         => $ogImage,
            'ogType'          => $ogType,
            'canonicalUrl'    => $canonicalUrl,
            'settings'        => $this->globalSettings,
            'menus'           => $this->globalMenus,
            'page'            => $page,
            'categories'      => $categories,
            'recentPosts'     => $recentPosts
        ]);
    }

    // Generate XML Sitemap
    public function sitemap() {
        header("Content-Type: application/xml; charset=utf-8");
        $posts = $this->postModel->getPublished(1000, 0); // up to 1000 posts
        $categories = $this->categoryModel->getAll();
        $pages = $this->pageModel->getAll(); // filtered by published status

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Beranda
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . BASE_URL . "</loc>\n";
        $xml .= "    <changefreq>daily</changefreq>\n";
        $xml .= "    <priority>1.0</priority>\n";
        $xml .= "  </url>\n";

        // Posts
        foreach ($posts as $p) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . BASE_URL . "/post/" . htmlspecialchars($p['slug']) . "</loc>\n";
            $xml .= "    <lastmod>" . date('Y-m-d', strtotime($p['created_at'])) . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        // Static Pages
        foreach ($pages as $pg) {
            if ($pg['status'] === 'published') {
                $xml .= "  <url>\n";
                $xml .= "    <loc>" . BASE_URL . "/page/" . htmlspecialchars($pg['slug']) . "</loc>\n";
                $xml .= "    <lastmod>" . date('Y-m-d', strtotime($pg['updated_at'] ?? $pg['created_at'])) . "</lastmod>\n";
                $xml .= "    <changefreq>monthly</changefreq>\n";
                $xml .= "    <priority>0.7</priority>\n";
                $xml .= "  </url>\n";
            }
        }

        // Categories
        foreach ($categories as $cat) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . BASE_URL . "/category/" . htmlspecialchars($cat['slug']) . "</loc>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.6</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';
        echo $xml;
        exit;
    }
}

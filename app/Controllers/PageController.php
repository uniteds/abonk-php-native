<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Request;
use App\Models\Page;

/**
 * Page Controller (Admin Static Pages CRUD)
 */
class PageController extends Controller {
    private $pageModel;

    public function __construct() {
        Session::start();
        
        // Secure all routes in this controller
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', "Anda harus masuk terlebih dahulu!");
            $this->redirect('admin/login');
        }

        $this->pageModel = new Page();
    }

    // List pages in admin
    public function index() {
        $pages = $this->pageModel->getAll();
        $this->view('admin/pages/index', [
            'pages' => $pages
        ]);
    }

    // Create static page
    public function create() {
        $error = null;

        if (Request::isPost()) {
            $token = Request::post('csrf_token');
            if (!Session::validateCsrfToken($token)) {
                $error = "Kesalahan Validasi CSRF. Silakan coba lagi.";
            } else {
                $title   = Request::post('title');
                $content = $_POST['content'] ?? ''; // Trusted admin input
                $status  = Request::post('status');

                if (empty($title) || empty($content)) {
                    $error = "Judul dan Konten Halaman wajib diisi!";
                } else {
                    $slug = $this->generateSlug($title);
                    
                    $this->pageModel->create([
                        'title'   => $title,
                        'slug'    => $slug,
                        'content' => $content,
                        'status'  => $status
                    ]);

                    Session::setFlash('success', "Halaman statis baru berhasil ditambahkan!");
                    $this->redirect('admin/pages');
                }
            }
        }

        $csrfToken = Session::generateCsrfToken();

        $this->view('admin/pages/create', [
            'csrfToken' => $csrfToken,
            'error'     => $error
        ]);
    }

    // Edit static page
    public function edit($id) {
        $page = $this->pageModel->findById($id);

        if (!$page) {
            Session::setFlash('error', "Halaman tidak ditemukan.");
            $this->redirect('admin/pages');
        }

        $error = null;

        if (Request::isPost()) {
            $token = Request::post('csrf_token');
            if (!Session::validateCsrfToken($token)) {
                $error = "Kesalahan Validasi CSRF. Silakan coba lagi.";
            } else {
                $title   = Request::post('title');
                $content = $_POST['content'] ?? ''; // Trusted admin input
                $status  = Request::post('status');

                if (empty($title) || empty($content)) {
                    $error = "Judul dan Konten Halaman wajib diisi!";
                } else {
                    $slug = ($title === $page['title']) ? $page['slug'] : $this->generateSlug($title, $id);
                    
                    $this->pageModel->update($id, [
                        'title'   => $title,
                        'slug'    => $slug,
                        'content' => $content,
                        'status'  => $status
                    ]);

                    Session::setFlash('success', "Halaman statis berhasil diperbarui!");
                    $this->redirect('admin/pages');
                }
            }
        }

        $csrfToken = Session::generateCsrfToken();

        $this->view('admin/pages/edit', [
            'page'      => $page,
            'csrfToken' => $csrfToken,
            'error'     => $error
        ]);
    }

    // Delete static page
    public function delete($id) {
        $page = $this->pageModel->findById($id);

        if (!$page) {
            Session::setFlash('error', "Halaman tidak ditemukan.");
            $this->redirect('admin/pages');
        }

        try {
            $this->pageModel->delete($id);
            Session::setFlash('success', "Halaman '{$page['title']}' berhasil dihapus.");
        } catch (\Exception $e) {
            Session::setFlash('error', "Gagal menghapus halaman.");
        }

        $this->redirect('admin/pages');
    }

    // Slug generator helper
    private function generateSlug($string, $excludeId = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Check uniqueness
        $sql = "SELECT COUNT(*) FROM pages WHERE slug = :slug";
        $params = ['slug' => $slug];
        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $db = \App\Core\Database::getInstance();
        $count = $db->query($sql, $params)->fetchColumn();

        if ($count > 0) {
            $slug .= '-' . rand(10, 99);
        }

        return $slug;
    }
}

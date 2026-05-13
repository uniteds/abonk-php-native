<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Request;
use App\Models\Category;

/**
 * Category Controller (Admin Category CRUD)
 */
class CategoryController extends Controller {
    private $categoryModel;

    public function __construct() {
        Session::start();
        
        // Secure all routes in this controller
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', "Anda harus masuk terlebih dahulu!");
            $this->redirect('admin/login');
        }

        $this->categoryModel = new Category();
    }

    // List categories in admin
    public function index() {
        $categories = $this->categoryModel->getAllWithCount();
        $this->view('admin/categories/index', [
            'categories' => $categories
        ]);
    }

    // Create category
    public function create() {
        $error = null;

        if (Request::isPost()) {
            $token = Request::post('csrf_token');
            if (!Session::validateCsrfToken($token)) {
                $error = "Kesalahan Validasi CSRF. Silakan coba lagi.";
            } else {
                $name = Request::post('name');
                $description = Request::post('description');

                if (empty($name)) {
                    $error = "Nama Kategori wajib diisi!";
                } else {
                    $slug = $this->generateSlug($name);
                    
                    $this->categoryModel->create([
                        'name'        => $name,
                        'slug'        => $slug,
                        'description' => $description
                    ]);

                    Session::setFlash('success', "Kategori baru berhasil ditambahkan!");
                    $this->redirect('admin/categories');
                }
            }
        }

        $csrfToken = Session::generateCsrfToken();

        $this->view('admin/categories/create', [
            'csrfToken' => $csrfToken,
            'error'     => $error
        ]);
    }

    // Edit category
    public function edit($id) {
        $category = $this->categoryModel->findById($id);

        if (!$category) {
            Session::setFlash('error', "Kategori tidak ditemukan.");
            $this->redirect('admin/categories');
        }

        $error = null;

        if (Request::isPost()) {
            $token = Request::post('csrf_token');
            if (!Session::validateCsrfToken($token)) {
                $error = "Kesalahan Validasi CSRF. Silakan coba lagi.";
            } else {
                $name = Request::post('name');
                $description = Request::post('description');

                if (empty($name)) {
                    $error = "Nama Kategori wajib diisi!";
                } else {
                    $slug = ($name === $category['name']) ? $category['slug'] : $this->generateSlug($name, $id);
                    
                    $this->categoryModel->update($id, [
                        'name'        => $name,
                        'slug'        => $slug,
                        'description' => $description
                    ]);

                    Session::setFlash('success', "Kategori berhasil diperbarui!");
                    $this->redirect('admin/categories');
                }
            }
        }

        $csrfToken = Session::generateCsrfToken();

        $this->view('admin/categories/edit', [
            'category'  => $category,
            'csrfToken' => $csrfToken,
            'error'     => $error
        ]);
    }

    // Delete category
    public function delete($id) {
        $category = $this->categoryModel->findById($id);

        if (!$category) {
            Session::setFlash('error', "Kategori tidak ditemukan.");
            $this->redirect('admin/categories');
        }

        // Check if category is "Umum" (default category, should not be deleted to avoid issues)
        if ($category['slug'] === 'umum') {
            Session::setFlash('error', "Kategori 'Umum' adalah kategori sistem bawaan dan tidak boleh dihapus.");
            $this->redirect('admin/categories');
        }

        try {
            $this->categoryModel->delete($id);
            Session::setFlash('success', "Kategori berhasil dihapus.");
        } catch (\Exception $e) {
            // This catches the PDO foreign key restrict error beautifully!
            Session::setFlash('error', "Gagal menghapus! Kategori '{$category['name']}' masih memiliki beberapa artikel aktif terkait. Silakan hapus atau pindahkan artikel tersebut ke kategori lain terlebih dahulu.");
        }

        $this->redirect('admin/categories');
    }

    // Slug generator helper
    private function generateSlug($string, $excludeId = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Check uniqueness
        $sql = "SELECT COUNT(*) FROM categories WHERE slug = :slug";
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

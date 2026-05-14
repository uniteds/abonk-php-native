<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Request;
use App\Models\Post;
use App\Models\Category;

/**
 * Post Controller (Admin Article CRUD)
 */
class PostController extends Controller {
    private $postModel;
    private $categoryModel;

    public function __construct() {
        Session::start();
        
        // Authenticate session
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', "Anda harus masuk terlebih dahulu!");
            $this->redirect('admin/login');
        }

        $this->postModel = new Post();
        $this->categoryModel = new Category();
    }

    // List all posts in admin
    public function index() {
        $posts = $this->postModel->getAllForAdmin();
        $this->view('admin/posts/index', [
            'posts' => $posts
        ]);
    }

    // Create a new post
    public function create() {
        $error = null;
        $categories = $this->categoryModel->getAll();

        if (Request::isPost()) {
            $token = Request::post('csrf_token');
            if (!Session::validateCsrfToken($token)) {
                $error = "Kesalahan Validasi CSRF. Silakan coba lagi.";
            } else {
                $title = Request::post('title');
                $categoryId = Request::post('category_id');
                $content = Request::post('content');
                $status = Request::post('status');
                
                // Retrieve raw content unescaped for rich text editor if needed, but since we are sanitizing,
                // we can store standard HTML tags since only logged-in users (admin/editors) can write posts.
                // To allow admin to insert HTML (e.g. headings, images, links) we can extract raw $_POST['content'] safely.
                // Raw input should still be clean, but html_entity_decode or not htmlspecialchars-ing content is common for CMS text fields.
                $content = $_POST['content'] ?? ''; // Trusted admin input
                
                if (empty($title) || empty($categoryId) || empty($content)) {
                    $error = "Judul, Kategori, dan Konten wajib diisi!";
                } else {
                    // Auto-generate slug
                    $slug = $this->generateSlug($title);
                    
                    // Handle image upload
                    $imageName = null;
                    $imageFile = Request::file('image');
                    
                    if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
                        $imageName = $this->uploadImage($imageFile);
                        if (!$imageName) {
                            $error = "Gagal mengupload gambar. Pastikan format file adalah JPG/PNG/WEBP dan ukuran di bawah 2MB.";
                        }
                    }

                    if (!$error) {
                        $postData = [
                            'user_id'     => Session::get('user_id'),
                            'category_id' => $categoryId,
                            'title'       => $title,
                            'slug'        => $slug,
                            'content'     => $content,
                            'image'       => $imageName,
                            'status'      => $status
                        ];

                        $this->postModel->create($postData);
                        Session::setFlash('success', "Pos artikel berhasil dibuat!");
                        $this->redirect('admin/posts');
                    }
                }
            }
        }

        $csrfToken = Session::generateCsrfToken();

        $this->view('admin/posts/create', [
            'categories' => $categories,
            'csrfToken'  => $csrfToken,
            'error'      => $error
        ]);
    }

    // Edit an existing post
    public function edit($id) {
        $post = $this->postModel->findById($id);
        
        if (!$post) {
            Session::setFlash('error', "Artikel tidak ditemukan.");
            $this->redirect('admin/posts');
        }

        // Author/Admin check: Editors can only edit their own posts unless they are admin
        if (!Session::hasRole('admin') && $post['user_id'] != Session::get('user_id')) {
            Session::setFlash('error', "Akses Ditolak! Anda hanya dapat mengedit artikel buatan Anda sendiri.");
            $this->redirect('admin/posts');
        }

        $error = null;
        $categories = $this->categoryModel->getAll();

        if (Request::isPost()) {
            $token = Request::post('csrf_token');
            if (!Session::validateCsrfToken($token)) {
                $error = "Kesalahan Validasi CSRF. Silakan coba lagi.";
            } else {
                $title = Request::post('title');
                $categoryId = Request::post('category_id');
                $status = Request::post('status');
                $content = $_POST['content'] ?? ''; // Trusted admin input
                
                if (empty($title) || empty($categoryId) || empty($content)) {
                    $error = "Judul, Kategori, dan Konten wajib diisi!";
                } else {
                    // Generate new slug if title changed, or keep original
                    $slug = ($title === $post['title']) ? $post['slug'] : $this->generateSlug($title);
                    
                    $postData = [
                        'category_id' => $categoryId,
                        'title'       => $title,
                        'slug'        => $slug,
                        'content'     => $content,
                        'status'      => $status
                    ];

                    // Handle image upload
                    $imageFile = Request::file('image');
                    if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
                        $imageName = $this->uploadImage($imageFile);
                        if ($imageName) {
                            $postData['image'] = $imageName;
                            // Delete old image file
                            if (!empty($post['image'])) {
                                $this->deleteImageFile($post['image']);
                            }
                        } else {
                            $error = "Gagal mengupload gambar baru. Gunakan format JPG/PNG/WEBP < 2MB.";
                        }
                    }

                    if (!$error) {
                        $this->postModel->update($id, $postData);
                        Session::setFlash('success', "Pos artikel berhasil diperbarui!");
                        $this->redirect('admin/posts');
                    }
                }
            }
        }

        $csrfToken = Session::generateCsrfToken();

        $this->view('admin/posts/edit', [
            'post'       => $post,
            'categories' => $categories,
            'csrfToken'  => $csrfToken,
            'error'      => $error
        ]);
    }

    // Delete a post
    public function delete($id) {
        $post = $this->postModel->findById($id);
        
        if (!$post) {
            Session::setFlash('error', "Artikel tidak ditemukan.");
            $this->redirect('admin/posts');
        }

        // Check permission (Editors can only delete their own posts unless they are admin)
        if (!Session::hasRole('admin') && $post['user_id'] != Session::get('user_id')) {
            Session::setFlash('error', "Akses Ditolak! Anda hanya dapat menghapus artikel buatan Anda sendiri.");
            $this->redirect('admin/posts');
        }

        // Delete associated image from disk
        if (!empty($post['image'])) {
            $this->deleteImageFile($post['image']);
        }

        $this->postModel->delete($id);
        Session::setFlash('success', "Artikel berhasil dihapus.");
        $this->redirect('admin/posts');
    }

    // Helper to generate a clean URL slug from string
    private function generateSlug($string) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Make sure slug is unique by appending random numbers if needed (simplified check)
        $sql = "SELECT COUNT(*) FROM posts WHERE slug = :slug";
        $db = \App\Core\Database::getInstance();
        $count = $db->query($sql, ['slug' => $slug])->fetchColumn();
        
        if ($count > 0) {
            $slug .= '-' . rand(100, 999);
        }
        
        return $slug;
    }

    // Secure image uploader helper with Auto-Convert to WebP
    private function uploadImage($file) {
        // Create directory if it doesn't exist
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileTmp  = $file['tmp_name'];

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $mime = mime_content_type($fileTmp);

        // Validate extension, mime type, and file size (< 2MB)
        if (in_array($ext, $allowedExtensions) && in_array($mime, $allowedMimes) && $fileSize <= 2 * 1024 * 1024) {
            $secureName = uniqid('post_') . '.webp';
            $destPath = UPLOAD_DIR . '/' . $secureName;
            
            // Auto convert to WebP using GD library for maximum performance
            if (function_exists('imagewebp')) {
                $img = null;
                if ($mime === 'image/jpeg') {
                    $img = @imagecreatefromjpeg($fileTmp);
                } elseif ($mime === 'image/png') {
                    $img = @imagecreatefrompng($fileTmp);
                    if ($img) {
                        imagepalettetotruecolor($img);
                        imagealphablending($img, true);
                        imagesavealpha($img, true);
                    }
                } elseif ($mime === 'image/webp') {
                    $img = @imagecreatefromwebp($fileTmp);
                }

                if ($img) {
                    $success = imagewebp($img, $destPath, 85);
                    imagedestroy($img);
                    if ($success) {
                        return $secureName;
                    }
                }
            }

            // Fallback to standard move_uploaded_file if GD/imagewebp fails
            $secureNameFallback = uniqid('post_') . '.' . $ext;
            $destPathFallback = UPLOAD_DIR . '/' . $secureNameFallback;
            if (move_uploaded_file($fileTmp, $destPathFallback)) {
                return $secureNameFallback;
            }
        }

        return false;
    }

    // Delete image from directory helper
    private function deleteImageFile($filename) {
        $filePath = UPLOAD_DIR . '/' . $filename;
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }
}

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Request;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;

/**
 * Admin Controller (Dashboard & Settings)
 */
class AdminController extends Controller {
    private $postModel;
    private $settingModel;
    private $userModel;

    public function __construct() {
        Session::start();
        
        // Secure all routes in this controller
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', "Anda harus masuk terlebih dahulu!");
            $this->redirect('admin/login');
        }

        $this->postModel = new Post();
        $this->settingModel = new Setting();
        $this->userModel = new User();
    }

    // Dashboard index with stats
    public function index() {
        $stats = $this->postModel->getDashboardStats();
        $recentPosts = $this->postModel->getRecentForAdmin(5);
        
        $this->view('admin/dashboard', [
            'stats'       => $stats,
            'recentPosts' => $recentPosts
        ]);
    }

    // Site settings page
    public function settings() {
        // Only administrators can edit settings, editors cannot!
        if (!Session::hasRole('admin')) {
            Session::setFlash('error', "Akses Ditolak! Hanya Administrator yang dapat mengubah pengaturan.");
            $this->redirect('admin');
        }

        $error = null;
        $success = null;

        if (Request::isPost()) {
            $token = Request::post('csrf_token');
            if (!Session::validateCsrfToken($token)) {
                $error = "Kesalahan Validasi CSRF. Silakan coba lagi.";
            } else {
                $settingsData = [
                    'site_name'            => Request::post('site_name'),
                    'site_desc'            => Request::post('site_desc'),
                    'site_hero_title'      => Request::post('site_hero_title'),
                    'site_hero_desc'       => Request::post('site_hero_desc'),
                    'sponsor_url'          => Request::post('sponsor_url'),
                    'turnstile_site_key'   => Request::post('turnstile_site_key'),
                    'turnstile_secret_key' => Request::post('turnstile_secret_key'),
                ];

                if (empty($settingsData['site_name']) || empty($settingsData['site_desc'])) {
                    $error = "Nama Situs dan Deskripsi Situs wajib diisi!";
                } else {
                    // Handle site_logo upload
                    $logoFile = Request::file('site_logo');
                    if ($logoFile && $logoFile['error'] === UPLOAD_ERR_OK) {
                        $logoName = $this->uploadImage($logoFile, 'logo_');
                        if ($logoName) {
                            $settingsData['site_logo'] = $logoName;
                        } else {
                            $error = "Gagal mengupload logo. Gunakan format JPG/PNG/WEBP < 2MB.";
                        }
                    }

                    // Handle site_hero_image upload
                    $heroFile = Request::file('site_hero_image');
                    if ($heroFile && $heroFile['error'] === UPLOAD_ERR_OK) {
                        $heroName = $this->uploadImage($heroFile, 'hero_');
                        if ($heroName) {
                            $settingsData['site_hero_image'] = $heroName;
                        } else {
                            $error = "Gagal mengupload gambar banner. Gunakan format JPG/PNG/WEBP < 2MB.";
                        }
                    }

                    // Handle sponsor_image upload
                    $sponsorFile = Request::file('sponsor_image');
                    if ($sponsorFile && $sponsorFile['error'] === UPLOAD_ERR_OK) {
                        $sponsorName = $this->uploadImage($sponsorFile, 'sponsor_');
                        if ($sponsorName) {
                            $settingsData['sponsor_image'] = $sponsorName;
                        } else {
                            $error = "Gagal mengupload gambar banner sponsor. Gunakan format JPG/PNG/WEBP < 2MB.";
                        }
                    }

                    if (!$error) {
                        $this->settingModel->saveAll($settingsData);
                        Session::setFlash('success', "Pengaturan situs berhasil diperbarui!");
                        $this->redirect('admin/settings');
                    }
                }
            }
        }

        $settings = $this->settingModel->getAll();
        $csrfToken = Session::generateCsrfToken();

        $this->view('admin/settings', [
            'settings'  => $settings,
            'csrfToken' => $csrfToken,
            'error'     => $error
        ]);
    }

    // User profile view page
    public function profile() {
        $userId = Session::get('user_id');
        $user = $this->userModel->findById($userId);
        
        if (!$user) {
            Session::setFlash('error', "Data pengguna tidak ditemukan.");
            $this->redirect('admin');
        }

        $this->view('admin/profile', [
            'user' => $user
        ]);
    }

    // User profile editing page
    public function profileEdit() {
        $userId = Session::get('user_id');
        $user = $this->userModel->findById($userId);
        
        if (!$user) {
            Session::setFlash('error', "Data pengguna tidak ditemukan.");
            $this->redirect('admin');
        }

        $error = null;

        if (Request::isPost()) {
            $token = Request::post('csrf_token');
            if (!Session::validateCsrfToken($token)) {
                $error = "Kesalahan Validasi CSRF. Silakan coba lagi.";
            } else {
                $username = Request::post('username');
                $email = Request::post('email');
                $name = Request::post('name');
                $password = Request::post('password');
                $bio = Request::post('bio');
                $social_facebook = Request::post('social_facebook');
                $social_twitter = Request::post('social_twitter');
                $social_instagram = Request::post('social_instagram');
                $social_linkedin = Request::post('social_linkedin');
                $social_github = Request::post('social_github');

                // Check username and email uniqueness if changed
                $existingByUsername = $this->userModel->findByUsername($username);
                $existingByEmail = $this->userModel->findByEmail($email);

                if (empty($username) || empty($email)) {
                    $error = "Username dan Email wajib diisi!";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "Format email tidak valid!";
                } elseif ($existingByUsername && $existingByUsername['id'] != $userId) {
                    $error = "Username '{$username}' sudah digunakan oleh akun lain!";
                } elseif ($existingByEmail && $existingByEmail['id'] != $userId) {
                    $error = "Email '{$email}' sudah digunakan oleh akun lain!";
                } else {
                    $profileData = [
                        'username'         => $username,
                        'email'            => $email,
                        'name'             => $name,
                        'password'         => $password,
                        'bio'              => $bio,
                        'social_facebook'  => $social_facebook,
                        'social_twitter'   => $social_twitter,
                        'social_instagram' => $social_instagram,
                        'social_linkedin'  => $social_linkedin,
                        'social_github'    => $social_github
                    ];

                    // Handle profile photo upload
                    $imageFile = Request::file('profile_photo');
                    if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
                        $imageName = $this->uploadImage($imageFile);
                        if ($imageName) {
                            $profileData['profile_photo'] = $imageName;
                            // Delete old profile photo if exists
                            if (!empty($user['profile_photo'])) {
                                $this->deleteImageFile($user['profile_photo']);
                            }
                        } else {
                            $error = "Gagal mengupload foto profil. Gunakan format JPG/PNG/WEBP < 2MB.";
                        }
                    }

                    if (!$error) {
                        $this->userModel->updateProfile($userId, $profileData);
                        
                        // Update session variables
                        Session::set('user_username', $username);
                        Session::set('user_email', $email);
                        
                        Session::setFlash('success', "Profil Anda berhasil diperbarui!");
                        $this->redirect('admin/profile');
                    }
                }
            }
        }

        $csrfToken = Session::generateCsrfToken();

        $this->view('admin/profile_edit', [
            'user'      => $user,
            'csrfToken' => $csrfToken,
            'error'     => $error
        ]);
    }

    // Secure image uploader helper with Auto-Convert to WebP
    private function uploadImage($file, $prefix = 'profile_') {
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

        if (in_array($ext, $allowedExtensions) && in_array($mime, $allowedMimes) && $fileSize <= 2 * 1024 * 1024) {
            $secureName = uniqid($prefix) . '.webp';
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
            $secureNameFallback = uniqid($prefix) . '.' . $ext;
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

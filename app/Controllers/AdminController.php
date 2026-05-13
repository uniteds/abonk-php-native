<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Request;
use App\Models\Post;
use App\Models\Setting;

/**
 * Admin Controller (Dashboard & Settings)
 */
class AdminController extends Controller {
    private $postModel;
    private $settingModel;

    public function __construct() {
        Session::start();
        
        // Secure all routes in this controller
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', "Anda harus masuk terlebih dahulu!");
            $this->redirect('admin/login');
        }

        $this->postModel = new Post();
        $this->settingModel = new Setting();
    }

    // Dashboard index with stats
    public function index() {
        $stats = $this->postModel->getDashboardStats();
        
        $this->view('admin/dashboard', [
            'stats' => $stats
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
                    'site_name'       => Request::post('site_name'),
                    'site_desc'       => Request::post('site_desc'),
                    'site_hero_title' => Request::post('site_hero_title'),
                    'site_hero_desc'  => Request::post('site_hero_desc'),
                ];

                if (empty($settingsData['site_name']) || empty($settingsData['site_desc'])) {
                    $error = "Nama Situs dan Deskripsi Situs wajib diisi!";
                } else {
                    $this->settingModel->saveAll($settingsData);
                    Session::setFlash('success', "Pengaturan situs berhasil diperbarui!");
                    $this->redirect('admin/settings');
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
}

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Request;
use App\Models\NavigationMenu;

/**
 * NavigationMenu Controller (Admin Menu CRUD)
 */
class NavigationMenuController extends Controller {
    private $menuModel;

    public function __construct() {
        Session::start();
        
        // Secure all routes in this controller
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', "Anda harus masuk terlebih dahulu!");
            $this->redirect('admin/login');
        }

        // Only allow administrators to manage menu navigation
        if (!Session::hasRole('admin')) {
            Session::setFlash('error', "Hanya Administrator yang memiliki akses untuk mengelola menu navigasi!");
            $this->redirect('admin');
        }

        $this->menuModel = new NavigationMenu();
    }

    // List menus
    public function index() {
        $menus = $this->menuModel->getAll();
        $this->view('admin/menus/index', [
            'menus' => $menus
        ]);
    }

    // Create menu item
    public function create() {
        $error = null;

        if (Request::isPost()) {
            $token = Request::post('csrf_token');
            if (!Session::validateCsrfToken($token)) {
                $error = "Kesalahan Validasi CSRF. Silakan coba lagi.";
            } else {
                $label = Request::post('label');
                $url = Request::post('url');
                $orderNum = Request::post('order_num');

                if (empty($label)) {
                    $error = "Label Menu wajib diisi!";
                } elseif (empty($url)) {
                    $error = "URL Menu wajib diisi!";
                } else {
                    $this->menuModel->create([
                        'label'     => $label,
                        'url'       => $url,
                        'order_num' => (int)$orderNum
                    ]);

                    Session::setFlash('success', "Navigasi baru berhasil ditambahkan!");
                    $this->redirect('admin/menus');
                }
            }
        }

        $csrfToken = Session::generateCsrfToken();

        $this->view('admin/menus/create', [
            'csrfToken' => $csrfToken,
            'error'     => $error
        ]);
    }

    // Edit menu item
    public function edit($id) {
        $menu = $this->menuModel->findById($id);

        if (!$menu) {
            Session::setFlash('error', "Menu navigasi tidak ditemukan.");
            $this->redirect('admin/menus');
        }

        $error = null;

        if (Request::isPost()) {
            $token = Request::post('csrf_token');
            if (!Session::validateCsrfToken($token)) {
                $error = "Kesalahan Validasi CSRF. Silakan coba lagi.";
            } else {
                $label = Request::post('label');
                $url = Request::post('url');
                $orderNum = Request::post('order_num');

                if (empty($label)) {
                    $error = "Label Menu wajib diisi!";
                } elseif (empty($url)) {
                    $error = "URL Menu wajib diisi!";
                } else {
                    $this->menuModel->update($id, [
                        'label'     => $label,
                        'url'       => $url,
                        'order_num' => (int)$orderNum
                    ]);

                    Session::setFlash('success', "Navigasi berhasil diperbarui!");
                    $this->redirect('admin/menus');
                }
            }
        }

        $csrfToken = Session::generateCsrfToken();

        $this->view('admin/menus/edit', [
            'menu'      => $menu,
            'csrfToken' => $csrfToken,
            'error'     => $error
        ]);
    }

    // Delete menu item
    public function delete($id) {
        $menu = $this->menuModel->findById($id);

        if (!$menu) {
            Session::setFlash('error', "Menu navigasi tidak ditemukan.");
            $this->redirect('admin/menus');
        }

        $this->menuModel->delete($id);
        Session::setFlash('success', "Navigasi berhasil dihapus.");
        $this->redirect('admin/menus');
    }
}

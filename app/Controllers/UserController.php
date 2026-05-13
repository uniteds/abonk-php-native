<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Request;
use App\Models\User;

/**
 * User Controller (Admin User CRUD)
 */
class UserController extends Controller {
    private $userModel;

    public function __construct() {
        Session::start();
        
        // Strict access control: Authenticated & Admin Role only!
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', "Anda harus masuk terlebih dahulu!");
            $this->redirect('admin/login');
        }

        if (!Session::hasRole('admin')) {
            Session::setFlash('error', "Akses Ditolak! Hanya Administrator yang dapat mengakses menu Pengguna.");
            $this->redirect('admin');
        }

        $this->userModel = new User();
    }

    // List users
    public function index() {
        $users = $this->userModel->getAll();
        $this->view('admin/users/index', [
            'users' => $users
        ]);
    }

    // Create user
    public function create() {
        $error = null;

        if (Request::isPost()) {
            $token = Request::post('csrf_token');
            if (!Session::validateCsrfToken($token)) {
                $error = "Kesalahan Validasi CSRF. Silakan coba lagi.";
            } else {
                $username = Request::post('username');
                $email = Request::post('email');
                $password = Request::post('password');
                $role = Request::post('role');

                if (empty($username) || empty($email) || empty($password)) {
                    $error = "Semua bidang wajib diisi!";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "Format email tidak valid!";
                } elseif ($this->userModel->findByUsername($username)) {
                    $error = "Username '{$username}' sudah terdaftar!";
                } elseif ($this->userModel->findByEmail($email)) {
                    $error = "Email '{$email}' sudah terdaftar!";
                } else {
                    $this->userModel->create([
                        'username' => $username,
                        'email'    => $email,
                        'password' => $password,
                        'role'     => $role
                    ]);

                    Session::setFlash('success', "Pengguna baru berhasil ditambahkan!");
                    $this->redirect('admin/users');
                }
            }
        }

        $csrfToken = Session::generateCsrfToken();

        $this->view('admin/users/create', [
            'csrfToken' => $csrfToken,
            'error'     => $error
        ]);
    }

    // Edit user
    public function edit($id) {
        $user = $this->userModel->findById($id);

        if (!$user) {
            Session::setFlash('error', "Pengguna tidak ditemukan.");
            $this->redirect('admin/users');
        }

        $error = null;

        if (Request::isPost()) {
            $token = Request::post('csrf_token');
            if (!Session::validateCsrfToken($token)) {
                $error = "Kesalahan Validasi CSRF. Silakan coba lagi.";
            } else {
                $username = Request::post('username');
                $email = Request::post('email');
                $password = Request::post('password');
                $role = Request::post('role');

                // Check username uniqueness if changed
                $existingUserByUsername = $this->userModel->findByUsername($username);
                $existingUserByEmail = $this->userModel->findByEmail($email);

                if (empty($username) || empty($email)) {
                    $error = "Username dan Email wajib diisi!";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "Format email tidak valid!";
                } elseif ($existingUserByUsername && $existingUserByUsername['id'] != $id) {
                    $error = "Username '{$username}' sudah digunakan oleh akun lain!";
                } elseif ($existingUserByEmail && $existingUserByEmail['id'] != $id) {
                    $error = "Email '{$email}' sudah digunakan oleh akun lain!";
                } else {
                    $this->userModel->update($id, [
                        'username' => $username,
                        'email'    => $email,
                        'password' => $password,
                        'role'     => $role
                    ]);

                    // If editing own profile, update session data
                    if ($id == Session::get('user_id')) {
                        Session::set('user_username', $username);
                        Session::set('user_email', $email);
                        Session::set('user_role', $role);
                    }

                    Session::setFlash('success', "Informasi pengguna berhasil diperbarui!");
                    $this->redirect('admin/users');
                }
            }
        }

        $csrfToken = Session::generateCsrfToken();

        $this->view('admin/users/edit', [
            'user'      => $user,
            'csrfToken' => $csrfToken,
            'error'     => $error
        ]);
    }

    // Delete user
    public function delete($id) {
        // Prevent deleting own account!
        if ($id == Session::get('user_id')) {
            Session::setFlash('error', "Akses Ditolak! Anda tidak diperbolehkan menghapus akun Anda sendiri yang sedang aktif.");
            $this->redirect('admin/users');
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            Session::setFlash('error', "Pengguna tidak ditemukan.");
            $this->redirect('admin/users');
        }

        $this->userModel->delete($id);
        Session::setFlash('success', "Pengguna '{$user['username']}' berhasil dihapus.");
        $this->redirect('admin/users');
    }
}

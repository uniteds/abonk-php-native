<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Request;
use App\Models\User;
use App\Models\Setting;

/**
 * Auth Controller
 */
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        Session::start();
        $this->userModel = new User();
    }

    // Render & handle login
    public function login() {
        // Redirect to admin if already logged in
        if (Session::isLoggedIn()) {
            $this->redirect('admin');
        }

        $error = null;

        if (Request::isPost()) {
            $token = Request::post('csrf_token');
            if (!Session::validateCsrfToken($token)) {
                $error = "Kesalahan Validasi CSRF. Silakan coba lagi.";
            } else {
                $settings = (new Setting())->getAll();
                $turnstileSecret = !empty($settings['turnstile_secret_key']) ? $settings['turnstile_secret_key'] : '1x0000000000000000000000000000000AA';
                $turnstileResponse = Request::post('cf-turnstile-response');

                // Verify with Cloudflare Turnstile
                $context = stream_context_create([
                    'http' => [
                        'method'  => 'POST',
                        'header'  => 'Content-Type: application/x-www-form-urlencoded',
                        'content' => http_build_query([
                            'secret'   => $turnstileSecret,
                            'response' => $turnstileResponse
                        ])
                    ]
                ]);
                $result = @file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $context);
                $json = $result ? json_decode($result, true) : null;

                if (!$json || empty($json['success'])) {
                    $error = "Validasi Keamanan Captcha gagal. Silakan selesaikan tantangan keamanan dengan benar.";
                } else {
                    $username = Request::post('username');
                    $password = Request::post('password');

                    if (empty($username) || empty($password)) {
                        $error = "Username/Email dan Password wajib diisi!";
                    } else {
                        $user = $this->userModel->authenticate($username, $password);
                        if ($user) {
                            // Store in session
                            Session::set('user_id', $user['id']);
                            Session::set('user_username', $user['username']);
                            Session::set('user_email', $user['email']);
                            Session::set('user_role', $user['role']);

                            Session::setFlash('success', "Selamat datang kembali, " . $user['username'] . "!");
                            $this->redirect('admin');
                        } else {
                            $error = "Username atau Password salah!";
                        }
                    }
                }
            }
        }

        // Generate dynamic CSRF token
        $csrfToken = Session::generateCsrfToken();

        $this->view('admin/login', [
            'error'     => $error,
            'csrfToken' => $csrfToken
        ]);
    }

    // Handle logout
    public function logout() {
        Session::destroy();
        // Start fresh session to set flash
        Session::start();
        Session::setFlash('success', "Anda telah berhasil keluar.");
        $this->redirect('admin/login');
    }
}

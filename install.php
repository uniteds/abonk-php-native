<?php
/**
 * CMS Auto Installer Script
 * Creates the database, tables, and populates initial settings & admin account.
 */

require_once __DIR__ . '/config/config.php';

// Check Security Lock: If .installed lock file exists, block access immediately!
if (file_exists(__DIR__ . '/.installed')) {
    die("<div style='font-family: sans-serif; max-width: 600px; margin: 80px auto; padding: 30px; background: #fff; border: 1px solid #e1e1e1; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center;'>
        <h1 style='color: #d9534f; margin-bottom: 15px;'>🛡️ Instalasi Terkunci!</h1>
        <p style='color: #555; line-height: 1.6; margin-bottom: 25px;'>Abonk CMS telah berhasil diinstal pada sistem ini dan saat ini dilindungi oleh berkas keamanan <strong>.installed</strong>. Akses ke skrip installer ini dinonaktifkan sepenuhnya untuk mencegah penimpaan atau perusakan basis data.</p>
        <p style='margin-bottom: 15px;'><a href='index.php' style='display: inline-block; padding: 10px 20px; background: #00A86B; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold;'>Menuju Halaman Beranda</a></p>
        <p><a href='admin/login' style='color: #00A86B; text-decoration: none; font-size: 14px;'>Atau Masuk ke Panel Admin</a></p>
    </div>");
}

$installed = false;
$error = null;
$success = null;

// Double check database existence
try {
    $checkPdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $stmt = $checkPdo->query("SHOW TABLES LIKE 'settings'");
    if ($stmt && $stmt->rowCount() > 0) {
        $settingCount = $checkPdo->query("SELECT COUNT(*) FROM `settings`")->fetchColumn();
        if ($settingCount > 0) {
            $installed = true;
        }
    }
} catch (Exception $e) {}

if (isset($_POST['install'])) {
    try {
        // 1. Connect to MySQL Server (Without DB Name first)
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );

        // 2. Create Database
        $dbName = DB_NAME;
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$dbName}`");

        // 3. Create Users Table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `email` VARCHAR(100) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `name` VARCHAR(100) DEFAULT NULL,
            `profile_photo` VARCHAR(255) DEFAULT NULL,
            `bio` TEXT DEFAULT NULL,
            `social_facebook` VARCHAR(255) DEFAULT NULL,
            `social_twitter` VARCHAR(255) DEFAULT NULL,
            `social_instagram` VARCHAR(255) DEFAULT NULL,
            `social_linkedin` VARCHAR(255) DEFAULT NULL,
            `social_github` VARCHAR(255) DEFAULT NULL,
            `role` ENUM('admin', 'editor') DEFAULT 'editor',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // 4. Create Categories Table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `categories` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `slug` VARCHAR(100) NOT NULL UNIQUE,
            `description` TEXT DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // 5. Create Posts Table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `posts` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `category_id` INT NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `slug` VARCHAR(255) NOT NULL UNIQUE,
            `content` TEXT NOT NULL,
            `image` VARCHAR(255) DEFAULT NULL,
            `status` ENUM('draft', 'published') DEFAULT 'draft',
            `is_featured` TINYINT(1) DEFAULT 0,
            `tags` VARCHAR(255) DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
            FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // 5b. Create Pages Table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `pages` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `slug` VARCHAR(255) NOT NULL UNIQUE,
            `content` TEXT NOT NULL,
            `status` ENUM('draft', 'published') DEFAULT 'draft',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // 6. Create Settings Table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `settings` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `setting_key` VARCHAR(50) NOT NULL UNIQUE,
            `setting_value` TEXT DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // 6b. Create Navigation Menus Table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `navigation_menus` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `label` VARCHAR(100) NOT NULL,
            `url` VARCHAR(255) NOT NULL,
            `order_num` INT DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Seed Default Navigation Menus
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `navigation_menus`");
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO `navigation_menus` (`label`, `url`, `order_num`) VALUES 
                ('Beranda', '" . BASE_URL . "', 1),
                ('Umum', '" . BASE_URL . "/category/umum', 2),
                ('Teknologi', '" . BASE_URL . "/category/teknologi', 3)
            ");
        }

        // 7. Seed Default Admin User (username: admin, password: admin123)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `username` = 'admin'");
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            $hashedPassword = password_hash('admin123', PASSWORD_BCRYPT);
            $insertUser = $pdo->prepare("INSERT INTO `users` (`username`, `email`, `password`, `role`) VALUES (:username, :email, :password, :role)");
            $insertUser->execute([
                'username' => 'admin',
                'email'    => 'admin@cms.local',
                'password' => $hashedPassword,
                'role'     => 'admin'
            ]);
        }

        // 8. Seed Default Category (General)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `categories` WHERE `slug` = 'umum'");
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            $insertCat = $pdo->prepare("INSERT INTO `categories` (`name`, `slug`, `description`) VALUES (:name, :slug, :description)");
            $insertCat->execute([
                'name'        => 'Umum',
                'slug'        => 'umum',
                'description' => 'Kategori artikel umum secara default.'
            ]);
        }

        // Seed second category for design demo
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `categories` WHERE `slug` = 'teknologi'");
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            $insertCat = $pdo->prepare("INSERT INTO `categories` (`name`, `slug`, `description`) VALUES (:name, :slug, :description)");
            $insertCat->execute([
                'name'        => 'Teknologi',
                'slug'        => 'teknologi',
                'description' => 'Kategori seputar teknologi modern dan pemrograman.'
            ]);
        }

        // 9. Seed Default Settings
        $defaultSettings = [
            'site_name'       => 'Abonk CMS',
            'site_desc'       => 'Sistem Manajemen Konten Native PHP OOP Tercepat & Elegan.',
            'site_hero_title' => 'Eksplorasi Ide Tanpa Batas',
            'site_hero_desc'  => 'Membangun platform penerbitan digital modern menggunakan pondasi kode PHP murni, berorientasi objek, aman, serta berkinerja tinggi.',
        ];

        $insertSetting = $pdo->prepare("INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES (:key, :value) ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`)");
        foreach ($defaultSettings as $key => $value) {
            $insertSetting->execute([
                'key'   => $key,
                'value' => $value
            ]);
        }

        // 10. Seed Demo Post
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `posts` WHERE `slug` = 'selamat-datang-di-abonk-cms'");
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            // Get user_id of 'admin' and category_id of 'Umum'
            $adminUser = $pdo->query("SELECT `id` FROM `users` WHERE `username` = 'admin'")->fetch();
            $generalCat = $pdo->query("SELECT `id` FROM `categories` WHERE `slug` = 'umum'")->fetch();
            
            if ($adminUser && $generalCat) {
                $insertPost = $pdo->prepare("INSERT INTO `posts` (`user_id`, `category_id`, `title`, `slug`, `content`, `image`, `status`) VALUES (:user_id, :category_id, :title, :slug, :content, :image, :status)");
                $insertPost->execute([
                    'user_id'     => $adminUser['id'],
                    'category_id' => $generalCat['id'],
                    'title'       => 'Selamat Datang di Abonk CMS!',
                    'slug'        => 'selamat-datang-di-abonk-cms',
                    'content'     => '<h2>Selamat Datang di Dunia Penerbitan Modern</h2><p>Ini adalah pos artikel pertama Anda yang dihasilkan secara otomatis setelah proses instalasi berhasil. Abonk CMS dirancang dari nol menggunakan PHP Native 8+ dengan pendekatan pemrograman berorientasi objek (OOP) yang bersih dan terstruktur.</p><p>Sistem ini menggunakan arsitektur <strong>Model-View-Controller (MVC)</strong> yang memisahkan logika data dari penyajian visual. Dengan begitu, kode Anda menjadi jauh lebih modular, terstruktur, mudah dikembangkan, dan aman.</p><h3>Fitur Keamanan Bawaan:</h3><ul><li><strong>Anti SQL Injection</strong> menggunakan PDO Prepared Statements untuk seluruh operasi database.</li><li><strong>Anti XSS (Cross-Site Scripting)</strong> dengan sanitasi ketat untuk seluruh variabel masukan dan keluaran halaman.</li><li><strong>Anti CSRF (Cross-Site Request Forgery)</strong> menggunakan token acak dinamis pada setiap form operasional admin.</li><li><strong>Enkripsi Handal</strong> untuk autentikasi user menggunakan hash password default sistem (Bcrypt).</li></ul><p>Silakan masuk ke panel admin untuk membuat artikel baru, mengelola kategori, mengganti nama website, atau menambah pengguna baru. Mulai sekarang, ciptakan mahakarya digital Anda sendiri!</p>',
                    'image'       => null,
                    'status'      => 'published'
                ]);
            }
        }

        $success = "CMS Berhasil Diinstal!";
        $installed = true;
        @file_put_contents(__DIR__ . '/.installed', 'INSTALLED_ON=' . date('Y-m-d H:i:s'));
    } catch (PDOException $e) {
        $error = "Gagal Menginstal Database: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Installer - Abonk CMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-bg: #FFF8E7;
            --color-accent: #00A86B;
            --color-cream: #051f14;
            --color-card: #ffffff;
            --color-border: rgba(5, 31, 20, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-cream);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .installer-card {
            background-color: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: 20px;
            padding: 40px;
            max-width: 580px;
            width: 100%;
            box-shadow: 0 15px 40px rgba(5, 31, 20, 0.04);
            position: relative;
            overflow: hidden;
        }

        .installer-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--color-accent), #00FF9D);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-tag {
            display: inline-block;
            background: rgba(0, 168, 107, 0.1);
            color: var(--color-accent);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        h1 {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            color: var(--color-cream);
        }

        .subtitle {
            font-size: 14px;
            color: rgba(5, 31, 20, 0.5);
            line-height: 1.5;
        }

        .info-box {
            background-color: var(--color-bg);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }

        .info-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--color-accent);
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .info-list {
            list-style: none;
        }

        .info-list li {
            font-size: 14px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            color: var(--color-cream);
        }

        .info-list li span.label {
            color: rgba(5, 31, 20, 0.5);
        }

        .info-list li span.val {
            font-weight: 600;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .alert-error {
            background-color: rgba(255, 75, 75, 0.08);
            border: 1px solid rgba(255, 75, 75, 0.15);
            color: #d13030;
        }

        .alert-success {
            background-color: rgba(0, 168, 107, 0.08);
            border: 1px solid rgba(0, 168, 107, 0.15);
            color: var(--color-accent);
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 16px;
            background-color: var(--color-accent);
            color: var(--color-bg);
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 168, 107, 0.2);
            background-color: #008a58;
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--color-cream);
            border: 1px solid var(--color-border);
            margin-top: 12px;
        }

        .btn-secondary:hover {
            background-color: rgba(5, 31, 20, 0.03);
            box-shadow: none;
        }

        .note {
            font-size: 12px;
            color: rgba(5, 31, 20, 0.4);
            text-align: center;
            margin-top: 20px;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <div class="installer-card">
        <div class="logo-container">
            <span class="logo-tag">PHP Native OOP</span>
            <h1>Abonk CMS</h1>
            <p class="subtitle">Skrip Inisialisasi Database & Setup Otomatis</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <strong>Gagal!</strong> <?= $error ?><br>
                <small style="display: block; margin-top: 5px; opacity: 0.8;">Pastikan MySQL Server Anda aktif dan pengaturan di <code>config/config.php</code> sudah benar.</small>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <strong>Berhasil!</strong> <?= $success ?><br>
                Database, tabel relasional, setelan default, dan akun administrator pertama telah sukses diinisialisasi.
            </div>

            <div class="info-box">
                <div class="info-title">Kredensial Administrator Baru:</div>
                <ul class="info-list">
                    <li>
                        <span class="label">Username Admin:</span>
                        <span class="val" style="color: var(--color-accent)">admin</span>
                    </li>
                    <li>
                        <span class="label">Password Admin:</span>
                        <span class="val" style="color: var(--color-accent)">admin123</span>
                    </li>
                    <li>
                        <span class="label">Database Terbentuk:</span>
                        <span class="val"><?= DB_NAME ?></span>
                    </li>
                </ul>
            </div>

            <a href="index.php" class="btn">Kunjungi Beranda</a>
            <a href="admin/login" class="btn btn-secondary">Masuk ke Panel Admin</a>
        <?php else: ?>
            <p style="font-size: 14px; line-height: 1.6; text-align: center; color: rgba(255, 248, 231, 0.8); margin-bottom: 20px;">
                Klik tombol di bawah ini untuk memulai proses konfigurasi otomatis. Skrip ini akan melakukan pemeriksaan koneksi, pembuatan database, pembuatan skema tabel relasi, pengisian data setelan (settings), serta pembuatan user default.
            </p>

            <div class="info-box">
                <div class="info-title">Pemeriksaan Konfigurasi Database:</div>
                <ul class="info-list">
                    <li>
                        <span class="label">Host Database:</span>
                        <span class="val"><?= DB_HOST ?></span>
                    </li>
                    <li>
                        <span class="label">User MySQL:</span>
                        <span class="val"><?= DB_USER ?></span>
                    </li>
                    <li>
                        <span class="label">Password MySQL:</span>
                        <span class="val"><?= DB_PASS === '' ? '(Kosong)' : '********' ?></span>
                    </li>
                    <li>
                        <span class="label">Nama DB Target:</span>
                        <span class="val"><?= DB_NAME ?></span>
                    </li>
                </ul>
            </div>

            <form method="POST">
                <button type="submit" name="install" class="btn">Mulai Instalasi Sekarang</button>
            </form>
        <?php endif; ?>

        <div class="note">
            Palet Warna Premium yang Digunakan:<br>
            <span style="color: #00A86B">#00A86B</span> (Jade Green) • <span style="color: #051f14">#FFF8E7</span> (Cosmic Latte Cream)
        </div>
    </div>

</body>
</html>

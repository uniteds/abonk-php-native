<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? \App\Core\Request::escape($title) . " - " : "" ?><?= \App\Core\Request::escape($settings['site_name'] ?? 'Abonk CMS') ?></title>
    <meta name="description" content="<?= \App\Core\Request::escape($settings['site_desc'] ?? 'Sistem Manajemen Konten Native PHP OOP') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

    <header>
        <div class="nav-container">
            <a href="<?= BASE_URL ?>" class="logo">
                <span>//</span> <?= \App\Core\Request::escape($settings['site_name'] ?? 'Abonk CMS') ?>
            </a>
            <ul class="nav-links">
                <?php if (!empty($menus)): ?>
                    <?php foreach ($menus as $menu): ?>
                        <li><a href="<?= \App\Core\Request::escape($menu['url']) ?>"><?= \App\Core\Request::escape($menu['label']) ?></a></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li><a href="<?= BASE_URL ?>">Beranda</a></li>
                <?php endif; ?>

                <?php if (\App\Core\Session::isLoggedIn()): ?>
                    <li><a href="<?= BASE_URL ?>/admin" class="nav-btn">Panel Admin</a></li>
                <?php else: ?>
                    <li><a href="<?= BASE_URL ?>/admin/login" class="nav-btn">Masuk</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

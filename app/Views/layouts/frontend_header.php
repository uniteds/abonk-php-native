<?php
// Prepare default SEO variables if not set by controller
$pageTitle = isset($title) ? \App\Core\Request::escape($title) . " - " . \App\Core\Request::escape($settings['site_name'] ?? 'Abonk CMS') : \App\Core\Request::escape($settings['site_name'] ?? 'Abonk CMS');
$metaDesc = isset($metaDescription) ? \App\Core\Request::escape($metaDescription) : \App\Core\Request::escape($settings['site_desc'] ?? 'Sistem Manajemen Konten Native PHP OOP');
$ogImg = isset($ogImage) ? \App\Core\Request::escape($ogImage) : BASE_URL . '/assets/uploads/kotapadang.jpeg';
$ogTyp = isset($ogType) ? \App\Core\Request::escape($ogType) : 'website';
$canonUrl = isset($canonicalUrl) ? \App\Core\Request::escape($canonicalUrl) : BASE_URL . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <meta name="description" content="<?= $metaDesc ?>">
    <link rel="canonical" href="<?= $canonUrl ?>">

    <!-- Open Graph (OG) Tags for Social Media (Facebook, WhatsApp, LinkedIn) -->
    <meta property="og:title" content="<?= $pageTitle ?>">
    <meta property="og:description" content="<?= $metaDesc ?>">
    <meta property="og:image" content="<?= $ogImg ?>">
    <meta property="og:type" content="<?= $ogTyp ?>">
    <meta property="og:url" content="<?= $canonUrl ?>">
    <meta property="og:site_name" content="<?= \App\Core\Request::escape($settings['site_name'] ?? 'Abonk CMS') ?>">

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $pageTitle ?>">
    <meta name="twitter:description" content="<?= $metaDesc ?>">
    <meta name="twitter:image" content="<?= $ogImg ?>">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

    <header>
        <div class="nav-container">
            <a href="<?= BASE_URL ?>" class="logo" style="display: flex; align-items: center; gap: 8px;">
                <?php if (!empty($settings['site_logo'])): ?>
                    <img src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($settings['site_logo']) ?>" alt="<?= \App\Core\Request::escape($settings['site_name'] ?? 'Logo') ?>" style="max-height: 38px; width: auto; object-fit: contain;">
                <?php else: ?>
                    <span>//</span> <?= \App\Core\Request::escape($settings['site_name'] ?? 'Abonk CMS') ?>
                <?php endif; ?>
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

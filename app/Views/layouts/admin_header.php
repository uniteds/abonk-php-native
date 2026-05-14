<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? \App\Core\Request::escape($title) : "Admin Panel" ?> - Abonk CMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head>
<body>

    <div class="admin-container">
        
        <!-- Sidebar Navigation -->
        <aside>
            <div class="sidebar-top" style="flex-grow: 1; overflow-y: auto; padding-right: 5px;">
                <a href="<?= BASE_URL ?>/admin" class="admin-logo">
                    <span>//</span> Abonk CMS
                </a>
                
                <div class="menu-title">Navigasi Utama</div>
                <ul class="sidebar-menu">
                    <li class="<?= (isset($activePage) && $activePage === 'dashboard') ? 'active' : '' ?>">
                        <a href="<?= BASE_URL ?>/admin">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                            Dashboard
                        </a>
                    </li>
                    <li class="<?= (isset($activePage) && $activePage === 'posts') ? 'active' : '' ?>">
                        <a href="<?= BASE_URL ?>/admin/posts">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            Pos Artikel
                        </a>
                    </li>
                    <li class="<?= (isset($activePage) && $activePage === 'categories') ? 'active' : '' ?>">
                        <a href="<?= BASE_URL ?>/admin/categories">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Kategori
                        </a>
                    </li>
                    <li class="<?= (isset($activePage) && $activePage === 'pages') ? 'active' : '' ?>">
                        <a href="<?= BASE_URL ?>/admin/pages">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"></path><path d="M14 3v5h5M16 13H8m8 4H8m6-8H8"></path></svg>
                            Halaman Statis
                        </a>
                    </li>
                    <li class="<?= (isset($activePage) && $activePage === 'profile') ? 'active' : '' ?>">
                        <a href="<?= BASE_URL ?>/admin/profile">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profil Saya
                        </a>
                    </li>
                </ul>

                <!-- Admin and settings menu (only for Admin role) -->
                <?php if (\App\Core\Session::hasRole('admin')): ?>
                    <div class="menu-title">Administrasi</div>
                    <ul class="sidebar-menu">
                        <li class="<?= (isset($activePage) && $activePage === 'menus') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>/admin/menus">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                Navigasi
                            </a>
                        </li>
                        <li class="<?= (isset($activePage) && $activePage === 'users') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>/admin/users">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                Pengguna
                            </a>
                        </li>
                        <li class="<?= (isset($activePage) && $activePage === 'settings') ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>/admin/settings">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Pengaturan
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="user-badge" style="margin-top: auto; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 12px; padding-bottom: 12px; border-bottom: 1px solid var(--color-border); margin-bottom: 8px;">
                    <div style="width: 38px; height: 38px; border-radius: 50%; background: var(--color-accent); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 15px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0, 168, 107, 0.2);">
                        <?= strtoupper(substr(\App\Core\Session::get('user_username'), 0, 1)) ?>
                    </div>
                    <div style="overflow: hidden;">
                        <div style="font-weight: 700; font-size: 14px; color: var(--color-dark); white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
                            <?= \App\Core\Request::escape(\App\Core\Session::get('user_username')) ?>
                        </div>
                        <div style="font-size: 10px; color: var(--color-accent); font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">
                            <?= \App\Core\Session::get('user_role') ?>
                        </div>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 2px;">
                    <a href="<?= BASE_URL ?>" class="badge-link">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        Kunjungi Situs
                    </a>
                    <a href="<?= BASE_URL ?>/admin/profile" class="badge-link">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profil Saya
                    </a>
                    <a href="<?= BASE_URL ?>/admin/logout" class="badge-link logout">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar Sesi
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content Area Wrapper -->
        <main>
            
            <!-- Quick Flash Message Render -->
            <?php if (\App\Core\Session::hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?= \App\Core\Session::getFlash('success') ?>
                </div>
            <?php endif; ?>

            <?php if (\App\Core\Session::hasFlash('error')): ?>
                <div class="alert alert-error">
                    <?= \App\Core\Session::getFlash('error') ?>
                </div>
            <?php endif; ?>

<?php 
$title = "Dashboard";
$activePage = "dashboard";
require_once __DIR__ . '/../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Dashboard Utama</h1>
        <p>Selamat datang kembali di panel administrasi Abonk CMS. Berikut adalah ikhtisar konten situs Anda.</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/posts/create" class="btn">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
        Tulis Artikel Baru
    </a>
</div>

<!-- Grid of Stats Cards with Hover Green Outlines -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Total Artikel</div>
        <div class="stat-value"><?= $stats['total_posts'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Terbit (Published)</div>
        <div class="stat-value" style="color: var(--color-accent);"><?= $stats['published_posts'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Draf (Draft)</div>
        <div class="stat-value" style="color: #ffaa00;"><?= $stats['draft_posts'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Kategori</div>
        <div class="stat-value"><?= $stats['total_categories'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Pengguna</div>
        <div class="stat-value"><?= $stats['total_users'] ?></div>
    </div>
</div>

<!-- Quick Link Panels -->
<div style="background-color: var(--color-card); border: 1px solid var(--color-border); border-radius: 16px; padding: 30px; margin-top: 20px;">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--color-accent);">Mulai Mengelola Konten:</h3>
    <p style="font-size: 14px; color: var(--color-dark); opacity: 0.75; line-height: 1.6; margin-bottom: 24px;">
        Gunakan menu sidebar untuk mengontrol seluruh konten aplikasi. Anda dapat membuat artikel baru, mengunggah gambar unggulan yang responsif, mengorganisasikan kategori, mendaftarkan kontributor/editor baru, dan memperbarui identitas global situs web Anda.
    </p>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="<?= BASE_URL ?>/admin/posts" class="btn btn-secondary">Kelola Pos Artikel</a>
        <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-secondary">Kelola Kategori</a>
        <?php if (\App\Core\Session::hasRole('admin')): ?>
            <a href="<?= BASE_URL ?>/admin/users" class="btn btn-secondary">Kelola Pengguna</a>
            <a href="<?= BASE_URL ?>/admin/settings" class="btn btn-secondary">Pengaturan Situs</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>

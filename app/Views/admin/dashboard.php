<?php 
$title = "Dashboard";
$activePage = "dashboard";
require_once __DIR__ . '/../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Dashboard Utama</h1>
        <p>Selamat datang kembali, <strong><?= \App\Core\Request::escape(\App\Core\Session::get('user_username')) ?></strong>! Berikut adalah ikhtisar dan aktivitas terkini dari situs Anda.</p>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="<?= BASE_URL ?>/admin/pages/create" class="btn btn-secondary">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"></path></svg>
            Halaman Baru
        </a>
        <a href="<?= BASE_URL ?>/admin/posts/create" class="btn">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
            Tulis Artikel
        </a>
    </div>
</div>

<!-- Grid of Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Total Artikel</div>
        <div class="stat-value"><?= $stats['total_posts'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Artikel Terbit</div>
        <div class="stat-value" style="color: var(--color-accent);"><?= $stats['published_posts'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Artikel Draf</div>
        <div class="stat-value" style="color: #ffaa00;"><?= $stats['draft_posts'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Kategori</div>
        <div class="stat-value"><?= $stats['total_categories'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Halaman Statis</div>
        <div class="stat-value" style="color: #0077cc;"><?= $stats['total_pages'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Pengguna Terdaftar</div>
        <div class="stat-value"><?= $stats['total_users'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Total Tayangan</div>
        <div class="stat-value" style="color: #9c27b0;"><?= number_format($stats['total_views'] ?? 0) ?></div>
    </div>
</div>

<!-- Recent Content Table -->
<div class="table-wrapper" style="margin-top: 10px;">
    <div style="padding: 24px; border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 18px; font-weight: 800; color: var(--color-dark);">Aktivitas Artikel Terkini</h3>
            <p style="font-size: 13px; color: rgba(5, 31, 20, 0.5); margin-top: 2px;">Daftar pos artikel yang paling baru ditambahkan atau diubah.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/posts" style="font-size: 13px; color: var(--color-accent); font-weight: 700; text-decoration: none;">Lihat Semua Artikel &rarr;</a>
    </div>
    
    <?php if (empty($recentPosts)): ?>
        <div style="padding: 40px; text-align: center; color: rgba(5, 31, 20, 0.5);">
            Belum ada artikel yang ditulis. Mulai buat artikel pertama Anda!
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Judul Artikel</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Status</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentPosts as $post): ?>
                    <tr>
                        <td>
                            <strong style="color: var(--color-dark);"><?= \App\Core\Request::escape($post['title']) ?></strong>
                        </td>
                        <td>
                            <span class="badge" style="background-color: rgba(5, 31, 20, 0.05); color: var(--color-dark);">
                                <?= \App\Core\Request::escape($post['category_name']) ?>
                            </span>
                        </td>
                        <td><?= \App\Core\Request::escape($post['author_name']) ?></td>
                        <td>
                            <?php if ($post['status'] === 'published'): ?>
                                <span class="badge badge-success">Terbit</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Draf</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d M Y', strtotime($post['created_at'])) ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="<?= BASE_URL ?>/admin/posts/edit/<?= $post['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                                <a href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($post['slug']) ?>" target="_blank" class="btn btn-secondary btn-sm">Lihat</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Quick Link Panels -->
<div style="background-color: var(--color-card); border: 1px solid var(--color-border); border-radius: 16px; padding: 30px; margin-top: 10px;">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--color-accent);">Pusat Kendali Pengelolaan CMS:</h3>
    <p style="font-size: 14px; color: var(--color-dark); opacity: 0.75; line-height: 1.6; margin-bottom: 24px;">
        Gunakan menu pintasan di bawah atau bilah navigasi samping untuk mengontrol seluruh konten dan struktur situs web Anda secara terpusat.
    </p>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="<?= BASE_URL ?>/admin/posts" class="btn btn-secondary">Kelola Pos Artikel</a>
        <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-secondary">Kelola Kategori</a>
        <a href="<?= BASE_URL ?>/admin/pages" class="btn btn-secondary">Halaman Statis</a>
        <?php if (\App\Core\Session::hasRole('admin')): ?>
            <a href="<?= BASE_URL ?>/admin/users" class="btn btn-secondary">Kelola Pengguna</a>
            <a href="<?= BASE_URL ?>/admin/settings" class="btn btn-secondary">Pengaturan Situs</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>

<?php 
$title = "Tambah Navigasi Baru";
$activePage = "menus";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Tambah Menu Navigasi Baru</h1>
        <p>Tambahkan tautan khusus ke halaman beranda, kategori tertentu, atau halaman eksternal.</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/menus" class="btn btn-secondary">
        &larr; Kembali ke Daftar
    </a>
</div>

<?php if (isset($error) && $error !== null): ?>
    <div class="alert alert-error" style="margin-bottom: 25px;">
        <?= \App\Core\Request::escape($error) ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <form action="<?= BASE_URL ?>/admin/menus/create" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div class="form-group">
            <label for="label">Label Navigasi</label>
            <input type="text" name="label" id="label" class="form-control" placeholder="Contoh: Beranda, Kategori Kuliner, Tentang Kami..." required autofocus value="<?= isset($_POST['label']) ? \App\Core\Request::escape($_POST['label']) : '' ?>">
            <p class="form-text">Nama teks yang akan diklik oleh pengunjung web Anda.</p>
        </div>

        <div class="form-group">
            <label for="url">Tautan (URL)</label>
            <input type="text" name="url" id="url" class="form-control" placeholder="Contoh: <?= BASE_URL ?> atau <?= BASE_URL ?>/category/cerita..." required value="<?= isset($_POST['url']) ? \App\Core\Request::escape($_POST['url']) : '' ?>">
            <p class="form-text">Bisa menggunakan URL lengkap (http://...) atau path relatif dimulai dengan <code><?= BASE_URL ?>/</code>.</p>
        </div>

        <div class="form-group">
            <label for="order_num">Urutan Tampil (Angka)</label>
            <input type="number" name="order_num" id="order_num" class="form-control" placeholder="Contoh: 1, 2, 3..." value="<?= isset($_POST['order_num']) ? \App\Core\Request::escape($_POST['order_num']) : '0' ?>">
            <p class="form-text">Angka yang lebih kecil akan dimunculkan lebih awal di posisi paling kiri pada menu atas.</p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Simpan Navigasi</button>
            <a href="<?= BASE_URL ?>/admin/menus" class="btn btn-secondary">Batalkan</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

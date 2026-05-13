<?php 
$title = "Edit Menu Navigasi";
$activePage = "menus";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Edit Menu Navigasi</h1>
        <p>Ubah label, tautan, atau urutan tampil item menu navigasi.</p>
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
    <form action="<?= BASE_URL ?>/admin/menus/edit/<?= $menu['id'] ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div class="form-group">
            <label for="label">Label Navigasi</label>
            <input type="text" name="label" id="label" class="form-control" placeholder="Contoh: Beranda, Kategori Kuliner, Tentang Kami..." required autofocus value="<?= \App\Core\Request::escape($menu['label']) ?>">
            <p class="form-text">Nama teks yang akan diklik oleh pengunjung web Anda.</p>
        </div>

        <div class="form-group">
            <label for="url">Tautan (URL)</label>
            <input type="text" name="url" id="url" class="form-control" placeholder="Contoh: <?= BASE_URL ?> atau <?= BASE_URL ?>/category/cerita..." required value="<?= \App\Core\Request::escape($menu['url']) ?>">
            <p class="form-text">Bisa menggunakan URL lengkap (http://...) atau path relatif dimulai dengan <code><?= BASE_URL ?>/</code>.</p>
        </div>

        <div class="form-group">
            <label for="order_num">Urutan Tampil (Angka)</label>
            <input type="number" name="order_num" id="order_num" class="form-control" placeholder="Contoh: 1, 2, 3..." value="<?= \App\Core\Request::escape($menu['order_num']) ?>">
            <p class="form-text">Angka yang lebih kecil akan dimunculkan lebih awal di posisi paling kiri pada menu atas.</p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Simpan Perubahan</button>
            <a href="<?= BASE_URL ?>/admin/menus" class="btn btn-secondary">Batalkan</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

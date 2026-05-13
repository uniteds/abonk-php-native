<?php 
$title = "Tambah Kategori Baru";
$activePage = "categories";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Tambah Kategori Baru</h1>
        <p>Klasifikasikan artikel blog Anda agar navigasi situs lebih rapi.</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-secondary">
        &larr; Kembali ke Daftar
    </a>
</div>

<?php if (isset($error) && $error !== null): ?>
    <div class="alert alert-error" style="margin-bottom: 25px;">
        <?= \App\Core\Request::escape($error) ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <form action="<?= BASE_URL ?>/admin/categories/create" method="POST">
        <!-- CSRF Protection -->
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div class="form-group">
            <label for="name">Nama Kategori</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Tuliskan nama kategori (contoh: Kuliner, Otomotif, Bisnis)..." required autofocus value="<?= isset($_POST['name']) ? \App\Core\Request::escape($_POST['name']) : '' ?>">
            <p class="form-text">Slug URL bersih akan dihasilkan secara otomatis (misal: "Kuliner Nusantara" &rarr; <code>kuliner-nusantara</code>).</p>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi Singkat (Opsional)</label>
            <textarea name="description" id="description" class="form-control" placeholder="Tuliskan penjelasan singkat mengenai topik artikel yang dicakup oleh kategori ini..." style="min-height: 100px;"><?= isset($_POST['description']) ? \App\Core\Request::escape($_POST['description']) : '' ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Simpan Kategori</button>
            <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-secondary">Batalkan</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

<?php 
$title = "Edit Kategori";
$activePage = "categories";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Edit Kategori: "<?= \App\Core\Request::escape($category['name']) ?>"</h1>
        <p>Perbarui informasi klasifikasi artikel blog Anda.</p>
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
    <form action="<?= BASE_URL ?>/admin/categories/edit/<?= $category['id'] ?>" method="POST">
        <!-- CSRF Protection -->
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div class="form-group">
            <label for="name">Nama Kategori</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Tuliskan nama kategori..." required autofocus value="<?= \App\Core\Request::escape($category['name']) ?>">
            <p class="form-text">Slug saat ini: <code>/category/<?= \App\Core\Request::escape($category['slug']) ?></code></p>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi Singkat (Opsional)</label>
            <textarea name="description" id="description" class="form-control" placeholder="Tuliskan penjelasan singkat..." style="min-height: 100px;"><?= \App\Core\Request::escape($category['description']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Perbarui Kategori</button>
            <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-secondary">Batalkan</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

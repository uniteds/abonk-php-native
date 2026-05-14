<?php 
$title = "Edit Halaman Statis";
$activePage = "pages";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Edit Halaman Statis</h1>
        <p>Perbarui judul, status, atau konten halaman statis Anda.</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/pages" class="btn btn-secondary">
        &larr; Kembali ke Daftar
    </a>
</div>

<?php if (isset($error) && $error !== null): ?>
    <div class="alert alert-error" style="margin-bottom: 25px;">
        <?= \App\Core\Request::escape($error) ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <form action="<?= BASE_URL ?>/admin/pages/edit/<?= $page['id'] ?>" method="POST">
        <!-- CSRF Protection -->
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div class="form-group">
            <label for="title">Judul Halaman</label>
            <input type="text" name="title" id="title" class="form-control" required value="<?= \App\Core\Request::escape($page['title']) ?>">
            <p class="form-text">Slug saat ini: <code><?= \App\Core\Request::escape($page['slug']) ?></code></p>
        </div>

        <div class="form-group">
            <label for="status">Status Penerbitan</label>
            <select name="status" id="status" class="form-control" required>
                <option value="published" <?= ($page['status'] === 'published') ? 'selected' : '' ?>>Terbitkan Langsung (Published)</option>
                <option value="draft" <?= ($page['status'] === 'draft') ? 'selected' : '' ?>>Simpan Sebagai Draf (Draft)</option>
            </select>
        </div>

        <?php 
        $isInMenu = \App\Core\Database::getInstance()->query("SELECT COUNT(*) FROM navigation_menus WHERE url = :url", ['url' => '/page/' . $page['slug']])->fetchColumn() > 0;
        ?>
        <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 15px; margin-bottom: 15px;">
            <input type="checkbox" name="add_to_navigation" id="add_to_navigation" value="1" <?= $isInMenu ? 'checked' : '' ?> style="width: auto; height: 20px;">
            <label for="add_to_navigation" style="margin-bottom: 0; font-weight: 600; cursor: pointer;">Tampilkan di Menu Navigasi Utama</label>
        </div>

        <div class="form-group">
            <label for="content">Isi Halaman</label>
            <textarea name="content" id="content" class="form-control editor-textarea" placeholder="Tuliskan isi halaman statis Anda di sini..."><?= \App\Core\Request::escape($page['content']) ?></textarea>
            <p class="form-text">Gunakan editor teks kaya (Rich Text Editor) di atas untuk memformat tulisan, judul, list, dan tabel dengan sangat mudah.</p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Simpan Perubahan</button>
            <a href="<?= BASE_URL ?>/admin/pages" class="btn btn-secondary">Batalkan</a>
        </div>
    </form>
</div>

<!-- CKEditor 5 Rich Text Editor CDN & Initialization -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'bulletedList', 'numberedList', 'blockQuote', '|',
                'insertTable', '|',
                'undo', 'redo'
            ],
            placeholder: 'Tuliskan isi halaman statis Anda di sini...'
        })
        .catch(error => {
            console.error(error);
        });
</script>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

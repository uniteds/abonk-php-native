<?php 
$title = "Tambah Halaman Statis Baru";
$activePage = "pages";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Tambah Halaman Statis</h1>
        <p>Buat halaman informasi baru untuk situs web Anda.</p>
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
    <form action="<?= BASE_URL ?>/admin/pages/create" method="POST">
        <!-- CSRF Protection -->
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div class="form-group">
            <label for="title">Judul Halaman</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Contoh: Tentang Kami, Hubungi Kami, Kebijakan Privasi..." required autofocus value="<?= isset($_POST['title']) ? \App\Core\Request::escape($_POST['title']) : '' ?>">
            <p class="form-text">Slug URL akan dibuat secara otomatis dari judul ini.</p>
        </div>

        <div class="form-group">
            <label for="status">Status Penerbitan</label>
            <select name="status" id="status" class="form-control" required>
                <option value="published" <?= (isset($_POST['status']) && $_POST['status'] === 'published') ? 'selected' : '' ?>>Terbitkan Langsung (Published)</option>
                <option value="draft" <?= (isset($_POST['status']) && $_POST['status'] === 'draft') ? 'selected' : '' ?> selected>Simpan Sebagai Draf (Draft)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="content">Isi Halaman</label>
            <textarea name="content" id="content" class="form-control editor-textarea" placeholder="Tuliskan isi halaman statis Anda di sini..."><?= isset($_POST['content']) ? \App\Core\Request::escape($_POST['content']) : '' ?></textarea>
            <p class="form-text">Gunakan editor teks kaya (Rich Text Editor) di atas untuk memformat tulisan, judul, list, dan tabel dengan sangat mudah.</p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Simpan Halaman</button>
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

<?php 
$title = "Tulis Artikel Baru";
$activePage = "posts";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Tulis Artikel Baru</h1>
        <p>Buat dan publikasikan buah pikiran Anda hari ini.</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/posts" class="btn btn-secondary">
        &larr; Kembali ke Daftar
    </a>
</div>

<?php if (isset($error) && $error !== null): ?>
    <div class="alert alert-error" style="margin-bottom: 25px;">
        <?= \App\Core\Request::escape($error) ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <form action="<?= BASE_URL ?>/admin/posts/create" method="POST" enctype="multipart/form-data">
        <!-- CSRF Protection -->
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div class="form-group">
            <label for="title">Judul Artikel</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Tuliskan judul artikel yang menarik..." required autofocus value="<?= isset($_POST['title']) ? \App\Core\Request::escape($_POST['title']) : '' ?>">
            <p class="form-text">Slug URL akan dibuat secara otomatis dari judul ini.</p>
        </div>

        <div class="form-group">
            <label for="category_id">Kategori Artikel</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="" disabled selected>-- Pilih Kategori --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= \App\Core\Request::escape($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Gambar Unggulan (Featured Image)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" style="padding: 10px;">
            <p class="form-text">Maksimal ukuran file adalah 2MB. Format yang didukung: JPG, PNG, WEBP.</p>
        </div>

        <div class="form-group">
            <label for="status">Status Penerbitan</label>
            <select name="status" id="status" class="form-control" required>
                <option value="published" <?= (isset($_POST['status']) && $_POST['status'] === 'published') ? 'selected' : '' ?>>Terbitkan Langsung (Published)</option>
                <option value="draft" <?= (isset($_POST['status']) && $_POST['status'] === 'draft') ? 'selected' : '' ?> selected>Simpan Sebagai Draf (Draft)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="content">Isi Artikel</label>
            <textarea name="content" id="content" class="form-control editor-textarea" placeholder="Tuliskan isi artikel Anda di sini secara mendalam..."><?= isset($_POST['content']) ? \App\Core\Request::escape($_POST['content']) : '' ?></textarea>
            <p class="form-text">Gunakan editor teks kaya (Rich Text Editor) di atas untuk memformat tulisan, judul, list, dan tabel dengan sangat mudah.</p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Simpan Artikel</button>
            <a href="<?= BASE_URL ?>/admin/posts" class="btn btn-secondary">Batalkan</a>
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
            placeholder: 'Tuliskan isi artikel Anda di sini secara mendalam...'
        })
        .catch(error => {
            console.error(error);
        });
</script>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

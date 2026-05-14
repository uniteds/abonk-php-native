<?php 
$title = "Edit Artikel";
$activePage = "posts";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Edit Artikel: "<?= \App\Core\Request::escape($post['title']) ?>"</h1>
        <p>Perbarui informasi atau konten artikel Anda di bawah ini.</p>
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
    <form action="<?= BASE_URL ?>/admin/posts/edit/<?= $post['id'] ?>" method="POST" enctype="multipart/form-data">
        <!-- CSRF Protection -->
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div class="form-group">
            <label for="title">Judul Artikel</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Tuliskan judul artikel..." required autofocus value="<?= \App\Core\Request::escape($post['title']) ?>">
            <p class="form-text">Slug saat ini: <code>/post/<?= \App\Core\Request::escape($post['slug']) ?></code></p>
        </div>

        <div class="form-group">
            <label for="category_id">Kategori Artikel</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($post['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= \App\Core\Request::escape($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Gambar Unggulan Saat Ini</label>
            <div style="margin-bottom: 12px;">
                <?php if (!empty($post['image'])): ?>
                    <img src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($post['image']) ?>" alt="Featured Image" style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 8px; border: 1px solid var(--color-border); display: block;">
                    <p class="form-text" style="color: var(--color-accent)">Mengupload gambar baru akan mengganti gambar ini secara otomatis.</p>
                <?php else: ?>
                    <span style="font-size: 13px; color: rgba(255,248,231,0.3); font-style: italic;">Tidak ada gambar unggulan saat ini.</span>
                <?php endif; ?>
            </div>
            
            <label for="image">Unggah Gambar Baru (Opsional)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" style="padding: 10px;">
            <p class="form-text">Biarkan kosong jika tidak ingin mengubah gambar. Maksimal 2MB.</p>
        </div>

        <div class="form-group">
            <label for="status">Status Penerbitan</label>
            <select name="status" id="status" class="form-control" required>
                <option value="published" <?= ($post['status'] === 'published') ? 'selected' : '' ?>>Terbitkan Langsung (Published)</option>
                <option value="draft" <?= ($post['status'] === 'draft') ? 'selected' : '' ?>>Simpan Sebagai Draf (Draft)</option>
            </select>
        </div>

        <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 15px; margin-bottom: 15px;">
            <input type="checkbox" name="is_featured" id="is_featured" value="1" <?= ($post['is_featured'] == 1) ? 'checked' : '' ?> style="width: auto; height: 20px;">
            <label for="is_featured" style="margin-bottom: 0; font-weight: 600; cursor: pointer;">Jadikan Pos Unggulan (Featured Post)</label>
        </div>

        <div class="form-group">
            <label for="tags">Tag Artikel (Pisahkan dengan koma)</label>
            <input type="text" name="tags" id="tags" class="form-control" placeholder="Contoh: teknologi, pemrograman, web native, php" value="<?= \App\Core\Request::escape($post['tags'] ?? '') ?>">
            <p class="form-text">Ketikkan kata kunci / tag untuk artikel ini, pisahkan dengan koma jika lebih dari satu.</p>
        </div>

        <div class="form-group">
            <label for="content">Isi Artikel</label>
            <textarea name="content" id="content" class="form-control editor-textarea" placeholder="Tuliskan isi artikel Anda..."><?= \App\Core\Request::escape($post['content']) ?></textarea>
            <p class="form-text">Gunakan editor teks kaya (Rich Text Editor) di atas untuk memformat tulisan, judul, list, dan tabel dengan sangat mudah.</p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Perbarui Artikel</button>
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

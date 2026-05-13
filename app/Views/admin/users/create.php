<?php 
$title = "Tambah Pengguna Baru";
$activePage = "users";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Tambah Pengguna Baru</h1>
        <p>Daftarkan kontributor baru untuk mengelola konten web Anda.</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/users" class="btn btn-secondary">
        &larr; Kembali ke Daftar
    </a>
</div>

<?php if (isset($error) && $error !== null): ?>
    <div class="alert alert-error" style="margin-bottom: 25px;">
        <?= \App\Core\Request::escape($error) ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <form action="<?= BASE_URL ?>/admin/users/create" method="POST">
        <!-- CSRF Protection -->
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Tuliskan username unik..." required autofocus value="<?= isset($_POST['username']) ? \App\Core\Request::escape($_POST['username']) : '' ?>">
            <p class="form-text">Hanya karakter huruf kecil, angka, dan underscore yang diperbolehkan, tanpa spasi.</p>
        </div>

        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="contoh: nama@email.com..." required value="<?= isset($_POST['email']) ? \App\Core\Request::escape($_POST['email']) : '' ?>">
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi (Password)</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Tuliskan kata sandi yang aman..." required minlength="6">
            <p class="form-text">Minimal panjang kata sandi adalah 6 karakter.</p>
        </div>

        <div class="form-group">
            <label for="role">Hak Akses (Role)</label>
            <select name="role" id="role" class="form-control" required>
                <option value="editor" <?= (isset($_POST['role']) && $_POST['role'] === 'editor') ? 'selected' : '' ?> selected>Editor (Hanya CRUD Pos sendiri)</option>
                <option value="admin" <?= (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : '' ?>>Administrator (Akses Penuh Semua Fitur)</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Simpan Pengguna</button>
            <a href="<?= BASE_URL ?>/admin/users" class="btn btn-secondary">Batalkan</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

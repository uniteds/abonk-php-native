<?php 
$title = "Edit Pengguna";
$activePage = "users";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Edit Pengguna: "<?= \App\Core\Request::escape($user['username']) ?>"</h1>
        <p>Perbarui kredensial atau peran hak akses pengguna di bawah ini.</p>
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
    <form action="<?= BASE_URL ?>/admin/users/edit/<?= $user['id'] ?>" method="POST">
        <!-- CSRF Protection -->
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Tuliskan username..." required autofocus value="<?= \App\Core\Request::escape($user['username']) ?>">
            <p class="form-text">Hanya karakter huruf kecil, angka, dan underscore yang diperbolehkan.</p>
        </div>

        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com..." required value="<?= \App\Core\Request::escape($user['email']) ?>">
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi (Password) Baru</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Ketik kata sandi baru jika ingin mengubah..." minlength="6">
            <p class="form-text">Biarkan kosong jika tidak ingin mengubah kata sandi saat ini.</p>
        </div>

        <div class="form-group">
            <label for="role">Hak Akses (Role)</label>
            <select name="role" id="role" class="form-control" required>
                <option value="editor" <?= ($user['role'] === 'editor') ? 'selected' : '' ?>>Editor (Hanya CRUD Pos sendiri)</option>
                <option value="admin" <?= ($user['role'] === 'admin') ? 'selected' : '' ?>>Administrator (Akses Penuh Semua Fitur)</option>
            </select>
            <?php if ($user['id'] == \App\Core\Session::get('user_id')): ?>
                <p class="form-text" style="color: #b87a00;">Peringatan: Mengubah peran Anda sendiri dapat membatasi akses administrasi Anda seketika setelah disimpan.</p>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Perbarui Pengguna</button>
            <a href="<?= BASE_URL ?>/admin/users" class="btn btn-secondary">Batalkan</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

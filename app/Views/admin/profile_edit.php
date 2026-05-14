<?php 
$title = "Edit Profil Saya";
$activePage = "profile";
require_once __DIR__ . '/../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Edit Profil Saya</h1>
        <p>Perbarui informasi identitas, kredensial masuk, foto profil, dan tautan sosial media Anda.</p>
    </div>
</div>

<?php if (isset($error) && $error !== null): ?>
    <div class="alert alert-error" style="margin-bottom: 25px;">
        <?= \App\Core\Request::escape($error) ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <form action="<?= BASE_URL ?>/admin/profile/edit" method="POST" enctype="multipart/form-data">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <!-- Foto Profil Section -->
        <h3 style="font-size: 16px; font-weight: 800; color: var(--color-accent); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">Foto Profil</h3>
        <div class="form-group" style="display: flex; align-items: center; gap: 25px; margin-bottom: 35px;">
            <div class="photo-preview" style="width: 100px; height: 100px; border-radius: 50%; background: #FFF8E7; border: 2px solid var(--color-accent); overflow: hidden; display: flex; align-items: center; justify-content: center; font-size: 32px; color: var(--color-accent); font-weight: 800; flex-shrink: 0;">
                <?php if (!empty($user['profile_photo'])): ?>
                    <img src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($user['profile_photo']) ?>" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                <?php endif; ?>
            </div>
            <div style="flex-grow: 1;">
                <label for="profile_photo" style="display: block; font-weight: 700; margin-bottom: 8px;">Unggah Foto Profil Baru</label>
                <input type="file" name="profile_photo" id="profile_photo" class="form-control" accept="image/png, image/jpeg, image/webp" style="padding: 10px;">
                <p class="form-text" style="margin-top: 6px;">Format yang didukung: JPG, PNG, WEBP. Ukuran maksimal 2MB.</p>
            </div>
        </div>

        <!-- Informasi Akun Utama -->
        <h3 style="font-size: 16px; font-weight: 800; color: var(--color-accent); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 40px; margin-bottom: 20px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">Informasi Akun</h3>

        <div class="form-group">
            <label for="name">Nama Lengkap (Display Name)</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= \App\Core\Request::escape($user['name'] ?? '') ?>" placeholder="Contoh: Farouk Ahmad">
            <p class="form-text">Nama tampilan yang akan muncul sebagai penulis pada artikel yang Anda buat.</p>
        </div>

        <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" required value="<?= \App\Core\Request::escape($user['username']) ?>">
            </div>
            <div>
                <label for="email">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" required value="<?= \App\Core\Request::escape($user['email']) ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi Baru (Password)</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah kata sandi">
            <p class="form-text">Isi bagian ini hanya jika Anda ingin mengganti kata sandi lama Anda.</p>
        </div>

        <div class="form-group">
            <label for="bio">Biografi Singkat (Bio)</label>
            <textarea name="bio" id="bio" class="form-control" rows="4" placeholder="Ceritakan sedikit tentang keahlian atau latar belakang Anda..."><?= \App\Core\Request::escape($user['bio'] ?? '') ?></textarea>
        </div>

        <!-- Tautan Sosial Media -->
        <h3 style="font-size: 16px; font-weight: 800; color: var(--color-accent); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 40px; margin-bottom: 20px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">Tautan Sosial Media</h3>

        <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label for="social_facebook">Facebook URL</label>
                <input type="url" name="social_facebook" id="social_facebook" class="form-control" value="<?= \App\Core\Request::escape($user['social_facebook'] ?? '') ?>" placeholder="https://facebook.com/username">
            </div>
            <div>
                <label for="social_twitter">Twitter / X URL</label>
                <input type="url" name="social_twitter" id="social_twitter" class="form-control" value="<?= \App\Core\Request::escape($user['social_twitter'] ?? '') ?>" placeholder="https://twitter.com/username">
            </div>
            <div>
                <label for="social_instagram">Instagram URL</label>
                <input type="url" name="social_instagram" id="social_instagram" class="form-control" value="<?= \App\Core\Request::escape($user['social_instagram'] ?? '') ?>" placeholder="https://instagram.com/username">
            </div>
            <div>
                <label for="social_linkedin">LinkedIn URL</label>
                <input type="url" name="social_linkedin" id="social_linkedin" class="form-control" value="<?= \App\Core\Request::escape($user['social_linkedin'] ?? '') ?>" placeholder="https://linkedin.com/in/username">
            </div>
            <div style="grid-column: span 2;">
                <label for="social_github">GitHub URL</label>
                <input type="url" name="social_github" id="social_github" class="form-control" value="<?= \App\Core\Request::escape($user['social_github'] ?? '') ?>" placeholder="https://github.com/username">
            </div>
        </div>

        <div class="form-actions" style="margin-top: 40px;">
            <button type="submit" class="btn">Simpan Perubahan</button>
            <a href="<?= BASE_URL ?>/admin/profile" class="btn btn-secondary">Batal Edit</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>

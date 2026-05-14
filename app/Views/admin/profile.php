<?php 
$title = "Profil Saya";
$activePage = "profile";
require_once __DIR__ . '/../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Profil Saya</h1>
        <p>Ringkasan informasi akun, identitas penulis, dan presensi sosial media Anda.</p>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/admin/profile/edit" class="btn">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit Profil Saya
        </a>
    </div>
</div>

<div class="profile-preview-card" style="background-color: var(--color-card); border: 1px solid var(--color-border); border-radius: 20px; padding: 40px; max-width: 800px; box-shadow: 0 10px 30px rgba(5, 31, 20, 0.03);">
    
    <!-- Bagian Atas: Avatar & Nama -->
    <div style="display: flex; flex-direction: column; align-items: center; text-align: center; border-bottom: 1px solid var(--color-border); padding-bottom: 30px; margin-bottom: 30px;">
        <div style="width: 120px; height: 120px; border-radius: 50%; background: #FFF8E7; border: 3px solid var(--color-accent); box-shadow: 0 8px 25px rgba(0, 168, 107, 0.2); overflow: hidden; display: flex; align-items: center; justify-content: center; font-size: 42px; color: var(--color-accent); font-weight: 800; margin-bottom: 20px;">
            <?php if (!empty($user['profile_photo'])): ?>
                <img src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($user['profile_photo']) ?>" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
                <?= strtoupper(substr($user['username'], 0, 1)) ?>
            <?php endif; ?>
        </div>
        
        <h2 style="font-size: 28px; font-weight: 800; color: var(--color-dark); margin-bottom: 6px;">
            <?= \App\Core\Request::escape($user['name'] ?: $user['username']) ?>
        </h2>
        
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
            <span style="font-weight: 600; color: rgba(5, 31, 20, 0.6); font-size: 15px;">@<?= \App\Core\Request::escape($user['username']) ?></span>
            <span class="badge badge-success"><?= strtoupper($user['role']) ?></span>
        </div>

        <div style="color: rgba(5, 31, 20, 0.8); font-size: 15px; display: flex; align-items: center; gap: 6px;">
            <svg width="16" height="16" fill="none" stroke="var(--color-accent)" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            <?= \App\Core\Request::escape($user['email']) ?>
        </div>
    </div>

    <!-- Bagian Tengah: Bio -->
    <div style="margin-bottom: 35px;">
        <h3 style="font-size: 14px; font-weight: 800; color: var(--color-accent); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;">Biografi Penulis</h3>
        <p style="font-size: 16px; line-height: 1.8; color: rgba(5, 31, 20, 0.8); background: #FFF8E7; padding: 20px 25px; border-radius: 12px; border-left: 4px solid var(--color-accent);">
            <?= !empty($user['bio']) ? nl2br(\App\Core\Request::escape($user['bio'])) : '<i>Pengguna ini belum menambahkan biografi.</i>' ?>
        </p>
    </div>

    <!-- Bagian Bawah: Tautan Sosial Media -->
    <div>
        <h3 style="font-size: 14px; font-weight: 800; color: var(--color-accent); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Tautan Sosial Media</h3>
        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
            <?php if (!empty($user['social_facebook'])): ?>
                <a href="<?= \App\Core\Request::escape($user['social_facebook']) ?>" target="_blank" class="btn btn-secondary" style="display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg>
                    Facebook
                </a>
            <?php endif; ?>

            <?php if (!empty($user['social_twitter'])): ?>
                <a href="<?= \App\Core\Request::escape($user['social_twitter']) ?>" target="_blank" class="btn btn-secondary" style="display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path></svg>
                    Twitter / X
                </a>
            <?php endif; ?>

            <?php if (!empty($user['social_instagram'])): ?>
                <a href="<?= \App\Core\Request::escape($user['social_instagram']) ?>" target="_blank" class="btn btn-secondary" style="display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line></svg>
                    Instagram
                </a>
            <?php endif; ?>

            <?php if (!empty($user['social_linkedin'])): ?>
                <a href="<?= \App\Core\Request::escape($user['social_linkedin']) ?>" target="_blank" class="btn btn-secondary" style="display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path><circle cx="4" cy="4" r="2" fill="currentColor"></circle></svg>
                    LinkedIn
                </a>
            <?php endif; ?>

            <?php if (!empty($user['social_github'])): ?>
                <a href="<?= \App\Core\Request::escape($user['social_github']) ?>" target="_blank" class="btn btn-secondary" style="display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22"></path></svg>
                    GitHub
                </a>
            <?php endif; ?>

            <?php if (empty($user['social_facebook']) && empty($user['social_twitter']) && empty($user['social_instagram']) && empty($user['social_linkedin']) && empty($user['social_github'])): ?>
                <p style="font-size: 14px; color: rgba(5, 31, 20, 0.5); font-style: italic;">Belum ada tautan sosial media yang ditambahkan.</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>

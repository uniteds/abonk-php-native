<?php 
$title = "Pengaturan Situs";
$activePage = "settings";
require_once __DIR__ . '/../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Pengaturan Situs Global</h1>
        <p>Sesuaikan identitas, metadata SEO, dan konten bagian depan website Anda.</p>
    </div>
</div>

<?php if (isset($error) && $error !== null): ?>
    <div class="alert alert-error" style="margin-bottom: 25px;">
        <?= \App\Core\Request::escape($error) ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <form action="<?= BASE_URL ?>/admin/settings" method="POST">
        <!-- CSRF Protection -->
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <h3 style="font-size: 16px; font-weight: 800; color: var(--color-accent); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">Identitas Situs (SEO)</h3>

        <div class="form-group">
            <label for="site_name">Nama Situs Web (Site Name)</label>
            <input type="text" name="site_name" id="site_name" class="form-control" required value="<?= \App\Core\Request::escape($settings['site_name'] ?? 'Abonk CMS') ?>">
            <p class="form-text">Nama utama situs web Anda yang muncul di bilah judul browser dan navigasi header.</p>
        </div>

        <div class="form-group">
            <label for="site_desc">Deskripsi SEO Situs Web (Meta Description)</label>
            <textarea name="site_desc" id="site_desc" class="form-control" required style="min-height: 80px;"><?= \App\Core\Request::escape($settings['site_desc'] ?? 'Platform Konten Premium Anda.') ?></textarea>
            <p class="form-text">Deskripsi penjelas ringkas situs web Anda yang digunakan oleh mesin pencari seperti Google untuk pratinjau hasil pencarian.</p>
        </div>

        <h3 style="font-size: 16px; font-weight: 800; color: var(--color-accent); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 40px; margin-bottom: 20px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">Konten Banner Beranda (Hero Section)</h3>

        <div class="form-group">
            <label for="site_hero_title">Judul Utama Banner (Hero Title)</label>
            <input type="text" name="site_hero_title" id="site_hero_title" class="form-control" required value="<?= \App\Core\Request::escape($settings['site_hero_title'] ?? 'Eksplorasi Ide Tanpa Batas') ?>">
            <p class="form-text">Teks besar yang menyapa pengunjung pertama kali saat mereka membuka beranda.</p>
        </div>

        <div class="form-group">
            <label for="site_hero_desc">Sub-Judul Deskripsi Banner (Hero Subtitle)</label>
            <textarea name="site_hero_desc" id="site_hero_desc" class="form-control" required style="min-height: 100px;"><?= \App\Core\Request::escape($settings['site_hero_desc'] ?? '') ?></textarea>
            <p class="form-text">Penjelasan detail di bawah judul besar banner untuk menarik perhatian pembaca.</p>
        </div>

        <div class="form-actions" style="margin-top: 40px;">
            <button type="submit" class="btn">Simpan Semua Pengaturan</button>
            <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">Batalkan</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>

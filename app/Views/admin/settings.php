<?php 
$title = "Pengaturan Situs";
$activePage = "settings";
require_once __DIR__ . '/../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Pengaturan Situs Global</h1>
        <p>Sesuaikan identitas, metadata SEO, logo, dan gambar latar banner beranda website Anda.</p>
    </div>
</div>

<?php if (isset($error) && $error !== null): ?>
    <div class="alert alert-error" style="margin-bottom: 25px;">
        <?= \App\Core\Request::escape($error) ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <form action="<?= BASE_URL ?>/admin/settings" method="POST" enctype="multipart/form-data">
        <!-- CSRF Protection -->
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <h3 style="font-size: 16px; font-weight: 800; color: var(--color-accent); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">Identitas & Visual Situs (SEO & Logo)</h3>

        <div class="form-group">
            <label for="site_name">Nama Situs Web (Site Name)</label>
            <input type="text" name="site_name" id="site_name" class="form-control" required value="<?= \App\Core\Request::escape($settings['site_name'] ?? 'Abonk CMS') ?>">
            <p class="form-text">Nama utama situs web Anda yang muncul di bilah judul browser dan navigasi header.</p>
        </div>

        <div class="form-group">
            <label for="site_logo">Logo Utama Situs (Opsional)</label>
            <?php if (!empty($settings['site_logo'])): ?>
                <div style="margin-bottom: 12px; max-width: 200px; padding: 12px; background: rgba(5,31,20,0.03); border: 1px solid var(--color-border); border-radius: 8px;">
                    <img src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($settings['site_logo']) ?>" alt="Logo" style="max-width: 100%; height: auto; display: block;">
                </div>
            <?php endif; ?>
            <input type="file" name="site_logo" id="site_logo" class="form-control" accept="image/jpeg,image/png,image/webp">
            <p class="form-text">Format JPG/PNG/WEBP maks 2MB. Jika tidak mengunggah logo, sistem otomatis menampilkan teks nama situs seperti biasa.</p>
        </div>

        <div class="form-group">
            <label for="site_desc">Deskripsi SEO Situs Web (Meta Description)</label>
            <textarea name="site_desc" id="site_desc" class="form-control" required style="min-height: 80px;"><?= \App\Core\Request::escape($settings['site_desc'] ?? 'Platform Konten Premium Anda.') ?></textarea>
            <p class="form-text">Deskripsi penjelas ringkas situs web Anda yang digunakan oleh mesin pencari seperti Google untuk pratinjau hasil pencarian.</p>
        </div>

        <h3 style="font-size: 16px; font-weight: 800; color: var(--color-accent); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 40px; margin-bottom: 20px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">Konten & Latar Banner Beranda (Hero Section)</h3>

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

        <div class="form-group">
            <label for="site_hero_image">Gambar Latar Belakang Banner (Hero Background Image)</label>
            <?php if (!empty($settings['site_hero_image'])): ?>
                <div style="margin-bottom: 12px; max-width: 350px; padding: 12px; background: rgba(5,31,20,0.03); border: 1px solid var(--color-border); border-radius: 8px;">
                    <img src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($settings['site_hero_image']) ?>" alt="Hero Background" style="max-width: 100%; height: auto; border-radius: 6px; display: block;">
                </div>
            <?php endif; ?>
            <input type="file" name="site_hero_image" id="site_hero_image" class="form-control" accept="image/jpeg,image/png,image/webp">
            <p class="form-text">Gambar latar belakang beresolusi tinggi untuk bagian atas halaman beranda. Format JPG/PNG/WEBP maks 2MB.</p>
        </div>

        <h3 style="font-size: 16px; font-weight: 800; color: var(--color-accent); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 40px; margin-bottom: 20px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">Manajemen Sponsor & Iklan Sidebar</h3>

        <div class="form-group">
            <label for="sponsor_url">URL Tautan Sponsor (Sponsor Link)</label>
            <input type="url" name="sponsor_url" id="sponsor_url" class="form-control" placeholder="https://css.web.id" value="<?= \App\Core\Request::escape($settings['sponsor_url'] ?? 'https://css.web.id') ?>">
            <p class="form-text">Tautan tujuan ketika banner iklan sponsor di sidebar diklik oleh pengunjung.</p>
        </div>

        <div class="form-group">
            <label for="sponsor_image">Gambar Banner Sponsor / Iklan</label>
            <?php if (!empty($settings['sponsor_image'])): ?>
                <div style="margin-bottom: 12px; max-width: 300px; padding: 12px; background: rgba(5,31,20,0.03); border: 1px solid var(--color-border); border-radius: 8px;">
                    <img src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($settings['sponsor_image']) ?>" alt="Sponsor Banner" style="max-width: 100%; height: auto; border-radius: 6px; display: block;">
                </div>
            <?php endif; ?>
            <input type="file" name="sponsor_image" id="sponsor_image" class="form-control" accept="image/jpeg,image/png,image/webp">
            <p class="form-text">Gambar banner iklan untuk diletakkan di sidebar. Format JPG/PNG/WEBP maks 2MB.</p>
        </div>

        <h3 style="font-size: 16px; font-weight: 800; color: var(--color-accent); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 40px; margin-bottom: 20px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">Pencegahan Bruteforce & Keamanan Login (Cloudflare Turnstile)</h3>

        <div class="form-group">
            <label for="turnstile_site_key">Site Key (Kunci Situs)</label>
            <input type="text" name="turnstile_site_key" id="turnstile_site_key" class="form-control" placeholder="1x00000000000000000000AA" value="<?= \App\Core\Request::escape($settings['turnstile_site_key'] ?? '1x00000000000000000000AA') ?>">
            <p class="form-text">Dapatkan dari dasbor Cloudflare Turnstile. Secara bawaan menggunakan kunci pengujian Cloudflare.</p>
        </div>

        <div class="form-group">
            <label for="turnstile_secret_key">Secret Key (Kunci Rahasia)</label>
            <input type="password" name="turnstile_secret_key" id="turnstile_secret_key" class="form-control" placeholder="1x0000000000000000000000000000000AA" value="<?= \App\Core\Request::escape($settings['turnstile_secret_key'] ?? '1x0000000000000000000000000000000AA') ?>">
            <p class="form-text">Kunci rahasia untuk memverifikasi keabsahan captcha di sisi server web.</p>
        </div>

        <div class="form-actions" style="margin-top: 40px;">
            <button type="submit" class="btn">Simpan Semua Pengaturan</button>
            <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">Batalkan</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>

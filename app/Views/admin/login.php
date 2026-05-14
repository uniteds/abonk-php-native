<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Abonk CMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>
<body class="login-body">

    <div class="login-card">
        <div class="login-header">
            <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--color-accent); display: block; margin-bottom: 8px;">// KONTROL PANEL</span>
            <h1>Abonk CMS</h1>
            <p>Silakan masuk menggunakan kredensial Anda.</p>
        </div>

        <?php if (isset($error) && $error !== null): ?>
            <div class="alert alert-error" style="margin-bottom: 20px;">
                <?= \App\Core\Request::escape($error) ?>
            </div>
        <?php endif; ?>

        <!-- Session-based success/info flashes -->
        <?php if (\App\Core\Session::hasFlash('success')): ?>
            <div class="alert alert-success" style="margin-bottom: 20px;">
                <?= \App\Core\Session::getFlash('success') ?>
            </div>
        <?php endif; ?>

        <?php if (\App\Core\Session::hasFlash('error')): ?>
            <div class="alert alert-error" style="margin-bottom: 20px;">
                <?= \App\Core\Session::getFlash('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/admin/login" method="POST">
            <!-- CSRF Token Input -->
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <div class="form-group">
                <label for="username">Username atau Email</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username/email..." required autofocus autocomplete="username">
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label for="password">Kata Sandi</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan kata sandi..." required autocomplete="current-password">
            </div>

            <?php 
            $settings = (new \App\Models\Setting())->getAll();
            $turnstileSiteKey = !empty($settings['turnstile_site_key']) ? $settings['turnstile_site_key'] : '1x00000000000000000000AA';
            ?>
            <div class="form-group" style="display: flex; justify-content: center; margin-bottom: 25px;">
                <div class="cf-turnstile" data-sitekey="<?= \App\Core\Request::escape($turnstileSiteKey) ?>"></div>
            </div>

            <button type="submit" class="btn" style="width: 100%; justify-content: center;">Masuk Sekarang</button>
            
            <a href="<?= BASE_URL ?>" class="btn btn-secondary" style="width: 100%; justify-content: center; margin-top: 12px; font-weight: 600;">&larr; Kembali ke Situs</a>
        </form>
    </div>

</body>
</html>

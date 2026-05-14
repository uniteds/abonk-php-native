<?php require_once __DIR__ . '/../layouts/frontend_header.php'; ?>

    <main class="bg-grey pb-30">
        <div class="container single-content">
            <div class="entry-header entry-header-style-1 mb-50 pt-50 text-center">
                <h1 class="entry-title mb-20 font-weight-900">
                    <?= \App\Core\Request::escape($page['title']) ?>
                </h1>
                <p class="text-muted">
                    Diperbarui pada: <strong><?= date('d M Y', strtotime($page['updated_at'])) ?></strong>
                </p>
            </div>

            <article class="entry-wraper mb-50" style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.03);">
                <div class="entry-main-content dropcap wow fadeIn animated">
                    <?= html_entity_decode($page['content']) ?>
                </div>

                <div style="margin-top: 50px; text-align: center;">
                    <a href="<?= BASE_URL ?>" class="btn btn-primary" style="padding: 12px 30px; font-weight: 700; text-decoration: none;">&larr; Kembali ke Beranda</a>
                </div>
            </article>
        </div>
    </main>

<?php require_once __DIR__ . '/../layouts/frontend_footer.php'; ?>

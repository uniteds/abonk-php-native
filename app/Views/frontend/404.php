<?php require_once __DIR__ . '/../layouts/frontend_header.php'; ?>

    <main class="bg-grey pt-80 pb-50">
        <div class="container">
            <div class="row pt-80 pb-80">
                <div class="col-lg-6 col-md-12 d-lg-block d-none pr-50 align-self-center">
                    <img src="<?= BASE_URL ?>/assets/imgs/theme/page-not-found.png" alt="404 Not Found">
                </div>
                <div class="col-lg-6 col-md-12 pl-50 text-md-center text-lg-left align-self-center">
                    <h1 class="mb-30 font-weight-900 page-404">404</h1>
                    <form action="<?= BASE_URL ?>" method="GET" class="search-form d-lg-flex open-search mb-30">
                        <i class="elegant-icon icon_search"></i>
                        <input class="form-control" name="q" type="text" placeholder="Cari artikel...">
                    </form>
                    <p>Halaman atau artikel yang Anda cari mungkin telah dipindahkan atau tidak lagi tersedia.<br> Silakan kembali ke <a href="<?= BASE_URL ?>">Beranda</a> atau lakukan penelusuran di atas.</p>
                    <div class="form-group mt-30">
                        <a class="btn btn-primary text-white" href="<?= BASE_URL ?>">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php require_once __DIR__ . '/../layouts/frontend_footer.php'; ?>

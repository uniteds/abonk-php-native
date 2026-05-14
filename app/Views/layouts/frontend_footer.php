    <!-- Start Footer -->
    <footer class="pt-50 pb-20 bg-grey">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="sidebar-widget wow fadeInUp animated mb-30">
                        <div class="widget-header-2 position-relative mb-30">
                            <h5 class="mt-5 mb-30">Tentang Kami</h5>
                        </div>
                        <div class="textwidget">
                            <p><?= \App\Core\Request::escape($settings['site_desc'] ?? 'Platform penerbitan digital modern menggunakan PHP Native OOP.') ?></p>
                            <p><strong class="color-black">Lokasi:</strong> Kota Padang, Indonesia</p>
                            <p><strong class="color-black">Surel:</strong> info@abonk-cms.id</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="sidebar-widget widget_categories wow fadeInUp animated mb-30" data-wow-delay="0.1s">
                        <div class="widget-header-2 position-relative mb-30">
                            <h5 class="mt-5 mb-30">Pintasan</h5>
                        </div>
                        <ul class="font-small">
                            <li class="cat-item cat-item-1"><a href="<?= BASE_URL ?>">Beranda</a></li>
                            <li class="cat-item cat-item-2"><a href="<?= BASE_URL ?>/page/tentang-kami">Tentang Kami</a></li>
                            <?php if (\App\Core\Session::isLoggedIn()): ?>
                                <li class="cat-item cat-item-3"><a href="<?= BASE_URL ?>/admin">Panel Admin</a></li>
                            <?php else: ?>
                                <li class="cat-item cat-item-3"><a href="<?= BASE_URL ?>/admin/login">Masuk</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="sidebar-widget widget_tagcloud wow fadeInUp animated mb-30" data-wow-delay="0.2s">
                        <div class="widget-header-2 position-relative mb-30">
                            <h5 class="mt-5 mb-30">Kategori Top</h5>
                        </div>
                        <div class="tagcloud mt-50">
                            <?php if (!empty($categories)): ?>
                                <?php foreach (array_slice($categories, 0, 8) as $cat): ?>
                                    <a class="tag-cloud-link" href="<?= BASE_URL ?>/category/<?= \App\Core\Request::escape($cat['slug']) ?>"><?= \App\Core\Request::escape($cat['name']) ?></a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="sidebar-widget widget_newsletter wow fadeInUp animated mb-30" data-wow-delay="0.3s">
                        <div class="widget-header-2 position-relative mb-30">
                            <h5 class="mt-5 mb-30">Buletin</h5>
                        </div>
                        <div class="newsletter">
                            <p class="font-medium">Dapatkan informasi artikel terbaru serta tips dan trik langsung ke kotak masuk surel Anda.</p>
                            <form class="input-group form-subcriber mt-30 d-flex" onsubmit="alert('Terima kasih telah mendaftar buletin!'); return false;">
                                <input type="email" class="form-control bg-white font-small" placeholder="Masukkan surel Anda..." required>
                                <button class="btn bg-primary text-white" type="submit">Langganan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-copy-right pt-30 mt-20 wow fadeInUp animated">
                <p class="float-md-start font-small text-muted">© <?= date('Y') ?>, <?= \App\Core\Request::escape($settings['site_name'] ?? 'Abonk CMS') ?>. Hak Cipta Dilindungi. </p>
                <p class="float-md-end font-small text-muted">
                    Dikembangkan dengan <span style="color: var(--color-accent); font-weight: 700;">PHP Native OOP & MySQL</span> | Abonk CMS
                </p>
            </div>
        </div>
    </footer>
    <!-- End Footer -->

    <div class="dark-mark"></div>
    
    <!-- Vendor JS-->
    <script src="<?= BASE_URL ?>/assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/popper.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/jquery.slicknav.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/slick.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/wow.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/jquery.ticker.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/jquery.vticker-min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/jquery.scrollUp.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/jquery.nice-select.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/jquery.magnific-popup.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/jquery.sticky.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/perfect-scrollbar.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/waypoints.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vendor/jquery.theia.sticky.js"></script>
    <!-- Stories JS -->
    <script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>

</html>

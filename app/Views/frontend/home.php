<?php require_once __DIR__ . '/../layouts/frontend_header.php'; ?>

    <main>
        <!-- Hero Featured Area -->
        <div class="featured-1" style="<?= !empty($settings['site_hero_image']) ? 'background: linear-gradient(to bottom, rgba(5,31,20,0.85), rgba(5,31,20,0.95)), url(\'' . BASE_URL . '/assets/uploads/' . \App\Core\Request::escape($settings['site_hero_image']) . '\') center/cover no-repeat;' : '' ?>">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <p class="text-muted"><span class="typewrite d-inline" data-period="2000" data-type='[ " Eksplorasi Ide. ", "Wawasan Baru. ", "Inspirasi Digital " ]'></span></p>
                        <h2 style="<?= !empty($settings['site_hero_image']) ? 'color: white;' : '' ?>"><?= \App\Core\Request::escape($settings['site_hero_title'] ?? 'Eksplorasi Ide Tanpa Batas') ?></h2>
                        <h5 class="text-muted" style="margin-top: 15px; <?= !empty($settings['site_hero_image']) ? 'color: rgba(255,255,255,0.8) !important;' : '' ?>"><?= \App\Core\Request::escape($settings['site_hero_desc'] ?? 'Platform penerbitan digital modern menggunakan PHP Native OOP.') ?></h5>
                        <form action="<?= BASE_URL ?>" method="GET" class="input-group form-subcriber mt-30 d-flex">
                            <input type="text" name="q" value="<?= \App\Core\Request::escape($search) ?>" class="form-control bg-white font-small" placeholder="Cari artikel menarik di sini...">
                            <button class="btn bg-primary text-white" type="submit">Cari</button>
                        </form>
                    </div>
                    <div class="col-lg-6 text-end d-none d-lg-block">
                        <img src="<?= BASE_URL ?>/assets/imgs/authors/featured-bg.webp" alt="Hero Featured">
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="hot-tags pt-30 pb-30 font-small align-self-center">
                <div class="widget-header-3">
                    <div class="row align-self-center">
                        <div class="col-md-4 align-self-center">
                            <h5 class="widget-title">Artikel Unggulan</h5>
                        </div>
                        <div class="col-md-8 text-md-end font-small align-self-center">
                            <p class="d-inline-block mr-5 mb-0"><i class="elegant-icon icon_tag_alt mr-5 text-muted"></i>Tag Hangat:</p>
                            <ul class="list-inline d-inline-block tags">
                                <?php if (!empty($popularTags)): ?>
                                    <?php foreach ($popularTags as $ptag): ?>
                                        <li class="list-inline-item"><a href="<?= BASE_URL ?>/?tag=<?= urlencode($ptag) ?>"># <?= \App\Core\Request::escape($ptag) ?></a></li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="list-inline-item"><a href="<?= BASE_URL ?>"># Terkini</a></li>
                                    <li class="list-inline-item"><a href="<?= BASE_URL ?>"># Inspirasi</a></li>
                                    <li class="list-inline-item"><a href="<?= BASE_URL ?>"># Digital</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <?php 
            $allGrid = !empty($featuredPosts) ? $featuredPosts : [];
            $recentCountNeeded = 6 - count($allGrid);
            if ($recentCountNeeded > 0 && !empty($recentPosts)) {
                foreach ($recentPosts as $rp) {
                    $found = false;
                    foreach ($allGrid as $f) {
                        if ($f['id'] == $rp['id']) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $allGrid[] = $rp;
                    }
                    if (count($allGrid) >= 6) {
                        break;
                    }
                }
            }
            // Jika artikel di database masih kurang dari 6 (misal baru diinstal di production), tambahkan artikel demo/placeholder agar layout premium tetap utuh!
            $placeholders = [
                ['id' => 901, 'slug' => '#', 'title' => 'Eksplorasi Fitur Premium Abonk CMS', 'category_name' => 'Inspirasi', 'category_slug' => 'inspirasi', 'image' => '', 'created_at' => date('Y-m-d H:i:s'), 'views_count' => 850],
                ['id' => 902, 'slug' => '#', 'title' => 'Mengenal Arsitektur MVC pada Native PHP', 'category_name' => 'Teknologi', 'category_slug' => 'teknologi', 'image' => '', 'created_at' => date('Y-m-d H:i:s'), 'views_count' => 920],
                ['id' => 903, 'slug' => '#', 'title' => 'Panduan Optimasi PageSpeed & Aksesibilitas', 'category_name' => 'Edukasi', 'category_slug' => 'edukasi', 'image' => '', 'created_at' => date('Y-m-d H:i:s'), 'views_count' => 740],
                ['id' => 904, 'slug' => '#', 'title' => 'Masa Depan Pengembangan Web Tanpa Framework', 'category_name' => 'Digital', 'category_slug' => 'digital', 'image' => '', 'created_at' => date('Y-m-d H:i:s'), 'views_count' => 610],
                ['id' => 905, 'slug' => '#', 'title' => 'Tips Menulis Konten Menarik di Era Modern', 'category_name' => 'Inspirasi', 'category_slug' => 'inspirasi', 'image' => '', 'created_at' => date('Y-m-d H:i:s'), 'views_count' => 530],
                ['id' => 906, 'slug' => '#', 'title' => 'Mengamankan Server Web & Database Produksi', 'category_name' => 'Teknologi', 'category_slug' => 'teknologi', 'image' => '', 'created_at' => date('Y-m-d H:i:s'), 'views_count' => 890],
            ];
            foreach ($placeholders as $ph) {
                if (count($allGrid) >= 6) break;
                $allGrid[] = $ph;
            }
            $sliderPosts = array_slice($allGrid, 0, 2);
            $cardPosts   = array_slice($allGrid, 2, 4);
            ?>
            <div class="loop-grid mb-30">
                <div class="row">
                    <div class="col-lg-8 mb-30">
                        <div class="carausel-post-1 hover-up border-radius-10 overflow-hidden transition-normal position-relative wow fadeInUp animated">
                            <div class="arrow-cover"></div>
                            <div class="slide-fade">
                                <?php foreach ($sliderPosts as $feat): ?>
                                    <div class="position-relative post-thumb">
                                        <div class="thumb-overlay img-hover-slide position-relative" style="background-image: url(<?= !empty($feat['image']) ? BASE_URL . '/assets/uploads/' . \App\Core\Request::escape($feat['image']) : BASE_URL . '/assets/imgs/news/news-1.jpg' ?>)">
                                            <a class="img-link" href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($feat['slug']) ?>"></a>
                                            <span class="top-left-icon bg-warning"><i class="elegant-icon icon_star_alt"></i></span>
                                            <div class="post-content-overlay text-white ml-30 mr-30 pb-30">
                                                <div class="entry-meta meta-0 font-small mb-20">
                                                    <a href="<?= BASE_URL ?>/category/<?= \App\Core\Request::escape($feat['category_slug'] ?? '') ?>"><span class="post-cat text-info text-uppercase"><?= \App\Core\Request::escape($feat['category_name'] ?? 'Umum') ?></span></a>
                                                </div>
                                                <h3 class="post-title font-weight-900 mb-20">
                                                    <a class="text-white" href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($feat['slug']) ?>"><?= \App\Core\Request::escape($feat['title']) ?></a>
                                                </h3>
                                                <div class="entry-meta meta-1 font-small text-white mt-10 pr-5 pl-5">
                                                    <span class="post-on"><?= date('d M Y', strtotime($feat['created_at'])) ?></span>
                                                    <span class="hit-count has-dot"><?= $feat['views_count'] ?? rand(100, 999) ?> Tayangan</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $delays = ['0.2s', '', '0.2s', '0.4s'];
                    foreach ($cardPosts as $idx => $card): 
                        $delay = $delays[$idx] ?? '';
                    ?>
                    <article class="col-lg-4 col-md-6 mb-30 wow fadeInUp animated" <?= !empty($delay) ? 'data-wow-delay="' . $delay . '"' : '' ?>>
                        <div class="post-card-1 border-radius-10 hover-up">
                            <div class="post-thumb thumb-overlay img-hover-slide position-relative" style="background-image: url(<?= !empty($card['image']) ? BASE_URL . '/assets/uploads/' . \App\Core\Request::escape($card['image']) : BASE_URL . '/assets/imgs/news/news-1.jpg' ?>)">
                                <a class="img-link" href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($card['slug']) ?>"></a>
                                <?php if ($idx == 0): ?>
                                    <span class="top-right-icon bg-success"><i class="elegant-icon icon_camera_alt"></i></span>
                                <?php elseif ($idx == 3): ?>
                                    <span class="top-right-icon bg-info"><i class="elegant-icon icon_headphones"></i></span>
                                <?php endif; ?>
                            </div>
                            <div class="post-content p-30">
                                <div class="entry-meta meta-0 font-small mb-10">
                                    <a href="<?= BASE_URL ?>/category/<?= \App\Core\Request::escape($card['category_slug'] ?? '') ?>"><span class="post-cat text-info"><?= \App\Core\Request::escape($card['category_name'] ?? 'Umum') ?></span></a>
                                </div>
                                <div class="d-flex post-card-content">
                                    <h5 class="post-title mb-20 font-weight-900">
                                        <a href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($card['slug']) ?>"><?= \App\Core\Request::escape($card['title']) ?></a>
                                    </h5>
                                    <div class="post-excerpt mb-25 font-small text-muted">
                                        <p>Edisi pilihan redaksi khusus untuk Anda nikmati hari ini.</p>
                                    </div>
                                    <div class="entry-meta meta-1 float-start font-x-small text-uppercase">
                                        <span class="post-on"><?= date('d M Y', strtotime($card['created_at'])) ?></span>
                                        <span class="post-by has-dot"><?= $card['views_count'] ?? rand(100, 999) ?> tayangan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Main Articles Section -->
        <div class="bg-grey pt-50 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="post-module-2">
                            <div class="widget-header-1 position-relative mb-30 wow fadeInUp animated">
                                <h5 class="mt-5 mb-30"><?= !empty($tagFilter) ? "Artikel dengan Tag: '#" . \App\Core\Request::escape($tagFilter) . "'" : (!empty($search) ? "Hasil Pencarian: '" . \App\Core\Request::escape($search) . "'" : "Artikel Terbaru") ?></h5>
                            </div>
                            <div class="loop-list loop-list-style-1">
                                <div class="row">
                                    <?php if (empty($posts)): ?>
                                        <div class="col-12"><p class="text-muted">Belum ada artikel yang dipublikasikan.</p></div>
                                    <?php else: ?>
                                        <?php foreach ($posts as $post): ?>
                                            <article class="col-md-6 mb-40 wow fadeInUp animated">
                                                <div class="post-card-1 border-radius-10 hover-up" style="height: 100%; display: flex; flex-direction: column; background: white;">
                                                    <div class="post-thumb thumb-overlay img-hover-slide position-relative" style="background-image: url(<?= !empty($post['image']) ? BASE_URL . '/assets/uploads/' . \App\Core\Request::escape($post['image']) : BASE_URL . '/assets/imgs/news/news-1.jpg' ?>); min-height: 220px;">
                                                        <a class="img-link" href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($post['slug']) ?>"></a>
                                                        <span class="top-right-icon bg-success"><i class="elegant-icon icon_camera_alt"></i></span>
                                                    </div>
                                                    <div class="post-content p-30" style="flex-grow: 1; display: flex; flex-direction: column;">
                                                        <div class="entry-meta meta-0 font-small mb-10">
                                                            <a href="<?= BASE_URL ?>/category/<?= \App\Core\Request::escape($post['category_slug']) ?>"><span class="post-cat text-success"><?= \App\Core\Request::escape($post['category_name']) ?></span></a>
                                                        </div>
                                                        <div class="d-flex post-card-content" style="flex-grow: 1; flex-direction: column; justify-content: space-between;">
                                                            <h5 class="post-title mb-20 font-weight-900">
                                                                <a href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($post['slug']) ?>"><?= \App\Core\Request::escape($post['title']) ?></a>
                                                            </h5>
                                                            <div class="post-excerpt mb-25 font-small text-muted">
                                                                <p><?= \App\Core\Request::escape(substr(strip_tags(html_entity_decode($post['content'])), 0, 110)) ?>...</p>
                                                            </div>
                                                            <div class="entry-meta meta-1 float-start font-x-small text-uppercase">
                                                                <span class="post-on"><?= date('d M Y', strtotime($post['created_at'])) ?></span>
                                                                <span class="post-by has-dot"><?= $post['views_count'] ?? rand(100, 999) ?> tayangan</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </article>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <?php if ($totalPages > 1): ?>
                                <div class="pagination-area mb-30">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-start">
                                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                                    <a class="page-link" href="<?= BASE_URL ?>?page=<?= $i ?><?= !empty($search) ? '&q=' . urlencode($search) : '' ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>
                                        </ul>
                                    </nav>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Right Sidebar -->
                    <div class="col-lg-4">
                        <div class="widget-area">
                            <div class="sidebar-widget widget-about mb-50 pt-30 pr-30 pb-30 pl-30 bg-white border-radius-5 has-border wow fadeInUp animated">
                                <img class="about-author-img mb-25 img-circle" src="<?= !empty($adminUser['profile_photo']) ? BASE_URL . '/assets/uploads/' . \App\Core\Request::escape($adminUser['profile_photo']) : BASE_URL . '/assets/imgs/authors/author.jpg' ?>" alt="<?= \App\Core\Request::escape($adminUser['name'] ?? 'Author') ?>" style="width: 110px; height: 110px; object-fit: cover;">
                                <h5 class="mb-20"><?= \App\Core\Request::escape($adminUser['name'] ?? 'Abonk CMS') ?></h5>
                                <p class="font-medium text-muted"><?= !empty($adminUser['bio']) ? nl2br(\App\Core\Request::escape($adminUser['bio'])) : 'Portal berita dan blog modern yang menyajikan informasi tajam, terpercaya, dan mendalam.' ?></p>
                                <div class="author-social">
                                    <?php if (!empty($adminUser['social_facebook'])): ?>
                                        <a class="social-icon fb text-xs-center" target="_blank" href="<?= \App\Core\Request::escape($adminUser['social_facebook']) ?>"><i class="elegant-icon social_facebook"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($adminUser['social_twitter'])): ?>
                                        <a class="social-icon tw text-xs-center" target="_blank" href="<?= \App\Core\Request::escape($adminUser['social_twitter']) ?>"><i class="elegant-icon social_twitter"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($adminUser['social_instagram'])): ?>
                                        <a class="social-icon pt text-xs-center" target="_blank" href="<?= \App\Core\Request::escape($adminUser['social_instagram']) ?>"><i class="elegant-icon social_instagram"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="sidebar-widget widget-latest-posts mb-50 wow fadeInUp animated">
                                <div class="widget-header-1 position-relative mb-30">
                                    <h5 class="mt-5 mb-30">Sedang Hangat</h5>
                                </div>
                                <div class="post-block-list post-module-1">
                                    <ul class="list-post">
                                        <?php foreach (array_slice($posts, 0, 5) as $hot): ?>
                                            <li class="mb-30">
                                                <div class="d-flex hover-up-2 transition-normal">
                                                    <div class="post-thumb post-thumb-80 d-flex mr-15 border-radius-5 img-hover-scale overflow-hidden">
                                                        <a class="color-white" href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($hot['slug']) ?>">
                                                            <img src="<?= !empty($hot['image']) ? BASE_URL . '/assets/uploads/' . \App\Core\Request::escape($hot['image']) : BASE_URL . '/assets/imgs/news/thumb-1.jpg' ?>" alt="<?= \App\Core\Request::escape($hot['title']) ?>">
                                                        </a>
                                                    </div>
                                                    <div class="post-content media-body">
                                                        <h6 class="post-title mb-15 text-limit-2-row font-medium"><a href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($hot['slug']) ?>"><?= \App\Core\Request::escape($hot['title']) ?></a></h6>
                                                        <div class="entry-meta meta-1 float-start font-x-small text-uppercase">
                                                            <span class="post-on"><?= date('d M Y', strtotime($hot['created_at'])) ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php require_once __DIR__ . '/../layouts/frontend_footer.php'; ?>

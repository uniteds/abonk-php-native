<?php require_once __DIR__ . '/../layouts/frontend_header.php'; ?>

    <main>
        <!-- Category Archive Header -->
        <div class="archive-header pt-50 pb-50 bg-grey mb-50">
            <div class="container">
                <div class="widget-header-1 position-relative mb-10">
                    <h5 class="mt-5 mb-10">Kategori</h5>
                </div>
                <h1 class="mb-15 font-weight-900"><?= \App\Core\Request::escape($category['name']) ?></h1>
                <div class="breadcrumb">
                    <span class="no-breadcrumb"><?= \App\Core\Request::escape($category['description'] ?? 'Eksplorasi artikel di bawah kategori ini.') ?></span>
                </div>
            </div>
        </div>

        <div class="bg-grey pt-50 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="post-module-2">
                            <div class="loop-list loop-list-style-1">
                                <div class="row">
                                    <?php if (empty($posts)): ?>
                                        <div class="col-12">
                                            <div style="background-color: white; padding: 40px; border-radius: 12px; text-align: center;">
                                                <p class="text-muted">Belum ada artikel dalam kategori ini.</p>
                                                <a href="<?= BASE_URL ?>" class="btn btn-primary mt-15">Kembali ke Beranda</a>
                                            </div>
                                        </div>
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
                                                                <span class="post-by has-dot">oleh <?= \App\Core\Request::escape($post['author_name']) ?></span>
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
                                                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                                    <a class="page-link" href="<?= BASE_URL ?>/category/<?= $category['slug'] ?>?page=<?= $i ?>"><?= $i ?></a>
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
                                    <h5 class="mt-5 mb-30">Artikel Terbaru</h5>
                                </div>
                                <div class="post-block-list post-module-1">
                                    <ul class="list-post">
                                        <?php foreach (array_slice($recentPosts, 0, 5) as $recent): ?>
                                            <li class="mb-30">
                                                <div class="d-flex hover-up-2 transition-normal">
                                                    <div class="post-thumb post-thumb-80 d-flex mr-15 border-radius-5 img-hover-scale overflow-hidden">
                                                        <a class="color-white" href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($recent['slug']) ?>">
                                                            <img src="<?= !empty($recent['image']) ? BASE_URL . '/assets/uploads/' . \App\Core\Request::escape($recent['image']) : BASE_URL . '/assets/imgs/news/thumb-1.jpg' ?>" alt="<?= \App\Core\Request::escape($recent['title']) ?>">
                                                        </a>
                                                    </div>
                                                    <div class="post-content media-body">
                                                        <h6 class="post-title mb-15 text-limit-2-row font-medium"><a href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($recent['slug']) ?>"><?= \App\Core\Request::escape($recent['title']) ?></a></h6>
                                                        <div class="entry-meta meta-1 float-start font-x-small text-uppercase">
                                                            <span class="post-on"><?= date('d M Y', strtotime($recent['created_at'])) ?></span>
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
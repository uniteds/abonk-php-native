<?php require_once __DIR__ . '/../layouts/frontend_header.php'; ?>

    <main class="bg-grey pb-30">
        <div class="container single-content">
            <div class="entry-header entry-header-style-1 mb-50 pt-50">
                <h1 class="entry-title mb-50 font-weight-900">
                    <?= \App\Core\Request::escape($post['title']) ?>
                </h1>
                <div class="row">
                    <div class="col-md-6">
                        <div class="entry-meta align-items-center meta-2 font-small color-muted">
                            <p class="mb-5">
                                <?php if (!empty($post['author_photo'])): ?>
                                    <span class="author-avatar"><img class="img-circle" src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($post['author_photo']) ?>" alt="<?= \App\Core\Request::escape($post['author_name']) ?>" style="width: 40px; height: 40px; object-fit: cover;"></span>
                                <?php else: ?>
                                    <span class="author-avatar"><img class="img-circle" src="<?= BASE_URL ?>/assets/imgs/authors/author.jpg" alt="<?= \App\Core\Request::escape($post['author_name']) ?>" style="width: 40px; height: 40px; object-fit: cover;"></span>
                                <?php endif; ?>
                                Oleh <span class="author-name font-weight-bold"><?= \App\Core\Request::escape($post['author_full_name'] ?: $post['author_name']) ?></span>
                            </p>
                            <span class="mr-10"> <?= date('d M Y', strtotime($post['created_at'])) ?></span>
                            <span class="has-dot"> <?= \App\Core\Request::escape($post['category_name']) ?></span>
                            <span class="has-dot"> <?= number_format($post['views_count'] ?? 0) ?> Tayangan</span>
                        </div>
                    </div>
                    <div class="col-md-6 text-end d-none d-md-inline">
                        <ul class="header-social-network d-inline-block list-inline mr-15">
                            <li class="list-inline-item text-muted"><span>Bagikan: </span></li>
                            <li class="list-inline-item"><a class="social-icon fb text-xs-center" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(BASE_URL . '/post/' . $post['slug']) ?>"><i class="elegant-icon social_facebook"></i></a></li>
                            <li class="list-inline-item"><a class="social-icon tw text-xs-center" target="_blank" href="https://twitter.com/intent/tweet?url=<?= urlencode(BASE_URL . '/post/' . $post['slug']) ?>&text=<?= urlencode($post['title']) ?>"><i class="elegant-icon social_twitter "></i></a></li>
                            <li class="list-inline-item"><a class="social-icon pt text-xs-center" target="_blank" href="#"><i class="elegant-icon social_pinterest "></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php if (!empty($post['image'])): ?>
                <figure class="image mb-30 m-auto text-center border-radius-10">
                    <img class="border-radius-10" src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($post['image']) ?>" alt="<?= \App\Core\Request::escape($post['title']) ?>" style="max-height: 550px; width: 100%; object-fit: cover;" />
                </figure>
            <?php endif; ?>

            <article class="entry-wraper mb-50">
                <div class="entry-main-content dropcap wow fadeIn animated" style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.03);">
                    <?= $post['content'] ?>
                </div>

                <!-- Tags Section -->
                <?php if (!empty($post['tags'])): ?>
                    <div class="entry-bottom mt-30 mb-30 wow fadeIn animated" style="background: white; padding: 20px 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <span class="font-weight-bold" style="color: var(--color-primary); margin-right: 10px;"><i class="elegant-icon icon_tag_alt"></i> Tag Artikel:</span>
                        <?php 
                        $tagsList = array_map('trim', explode(',', $post['tags']));
                        foreach ($tagsList as $t):
                            if (!empty($t)):
                        ?>
                            <a href="<?= BASE_URL ?>/?tag=<?= urlencode($t) ?>" class="btn btn-sm" style="background: var(--color-bg); color: var(--color-text); padding: 5px 15px; font-size: 13px; border-radius: 20px; transition: all 0.3s;">#<?= \App\Core\Request::escape($t) ?></a>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Author Bio Box -->
                <div class="author-bio p-30 mt-50 border-radius-10 bg-white wow fadeIn animated" style="box-shadow: 0 5px 15px rgba(0,0,0,0.03);">
                    <div class="author-image mb-30">
                        <?php if (!empty($post['author_photo'])): ?>
                            <img src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($post['author_photo']) ?>" alt="<?= \App\Core\Request::escape($post['author_name']) ?>" class="avatar" style="width: 90px; height: 90px; object-fit: cover;">
                        <?php else: ?>
                            <img src="<?= BASE_URL ?>/assets/imgs/authors/author.jpg" alt="<?= \App\Core\Request::escape($post['author_name']) ?>" class="avatar" style="width: 90px; height: 90px; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                    <div class="author-info">
                        <h4 class="font-weight-bold mb-20">
                            <span class="vcard author"><span class="fn"><?= \App\Core\Request::escape($post['author_full_name'] ?: $post['author_name']) ?></span></span>
                        </h4>
                        <h5 class="text-muted">Tentang Penulis</h5>
                        <div class="author-description text-muted">
                            <?= !empty($post['author_bio']) ? nl2br(\App\Core\Request::escape($post['author_bio'])) : 'Penulis reguler di Abonk CMS yang gemar berbagi wawasan, ide, dan informasi menarik.' ?>
                        </div>
                        <div class="author-social mt-15">
                            <ul class="author-social-icons">
                                <li class="author-social-link-facebook"><a href="#" target="_blank"><i class="elegant-icon social_facebook"></i></a></li>
                                <li class="author-social-link-twitter"><a href="#" target="_blank"><i class="elegant-icon social_twitter"></i></a></li>
                                <li class="author-social-link-instagram"><a href="#" target="_blank"><i class="elegant-icon social_instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Back button / navigation -->
                <div style="margin-top: 40px; text-align: center;">
                    <a href="<?= BASE_URL ?>" class="btn btn-primary" style="padding: 12px 30px; font-weight: 700; text-decoration: none;">&larr; Kembali ke Beranda</a>
                </div>
            </article>
        </div>
    </main>

<?php require_once __DIR__ . '/../layouts/frontend_footer.php'; ?>

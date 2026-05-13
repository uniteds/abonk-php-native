<?php require_once __DIR__ . '/../layouts/frontend_header.php'; ?>

<!-- Hero Section with Custom Theme Pattern -->
<section class="hero">
    <div class="hero-pattern"></div>
    <div class="hero-content">
        <h1><?= \App\Core\Request::escape($settings['site_hero_title'] ?? 'Eksplorasi Ide Tanpa Batas') ?></h1>
        <p><?= \App\Core\Request::escape($settings['site_hero_desc'] ?? 'Platform penerbitan digital modern menggunakan PHP Native OOP.') ?></p>
        
        <!-- Search Bar with Color Accent Button -->
        <form action="<?= BASE_URL ?>" method="GET" class="search-form">
            <input type="text" name="q" value="<?= \App\Core\Request::escape($search) ?>" class="search-input" placeholder="Cari artikel menarik di sini...">
            <button type="submit" class="search-btn">Cari</button>
        </form>
    </div>
</section>

<!-- Featured Grid Area (Only on Page 1, without search) -->
<?php if ($currentPage === 1 && empty($search) && count($posts) >= 1): ?>
    <section class="featured-section">
        <div class="featured-grid">
            
            <!-- Left Area: Featured Post (Large Card) -->
            <div class="featured-left">
                <?php $featured = $posts[0]; ?>
                <article class="featured-card-large">
                    <div class="featured-img-wrapper">
                        <?php if (!empty($featured['image'])): ?>
                            <img class="featured-img" src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($featured['image']) ?>" alt="<?= \App\Core\Request::escape($featured['title']) ?>">
                        <?php else: ?>
                            <div class="featured-placeholder">
                                <span><?= substr(\App\Core\Request::escape($featured['category_name']), 0, 3) ?></span>
                            </div>
                        <?php endif; ?>
                        <span class="post-badge"><?= \App\Core\Request::escape($featured['category_name']) ?></span>
                    </div>
                    
                    <div class="featured-body">
                        <div class="post-meta">
                            <span>
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <?= \App\Core\Request::escape($featured['author_name']) ?>
                            </span>
                            <span>
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <?= date('d M Y', strtotime($featured['created_at'])) ?>
                            </span>
                        </div>
                        <h2 class="featured-title">
                            <a href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($featured['slug']) ?>">
                                <?= \App\Core\Request::escape($featured['title']) ?>
                            </a>
                        </h2>
                        <div class="featured-excerpt">
                            <?= strip_tags($featured['content']) ?>
                        </div>
                        <a href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($featured['slug']) ?>" class="post-readmore">
                            Baca Selengkapnya
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </article>
            </div>
            
            <!-- Right Area: Recent/Related Posts (4 Small Cards) -->
            <div class="featured-right">
                <h3 class="sidebar-title">Rekomendasi Terbaru</h3>
                <div class="small-cards-stack">
                    <?php 
                    $smallPosts = array_slice($posts, 1, 4); 
                    if (empty($smallPosts)): 
                    ?>
                        <p style="color: var(--color-text-muted); font-size: 14px; font-style: italic;">Belum ada artikel tambahan.</p>
                    <?php 
                    else:
                        foreach ($smallPosts as $small): 
                    ?>
                        <article class="featured-card-small">
                            <div class="small-img-wrapper">
                                <?php if (!empty($small['image'])): ?>
                                    <img class="small-img" src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($small['image']) ?>" alt="<?= \App\Core\Request::escape($small['title']) ?>">
                                <?php else: ?>
                                    <div class="small-placeholder">
                                        <span><?= substr(\App\Core\Request::escape($small['category_name']), 0, 2) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="small-body">
                                <span class="small-cat-badge"><?= \App\Core\Request::escape($small['category_name']) ?></span>
                                <h4 class="small-title">
                                    <a href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($small['slug']) ?>">
                                        <?= \App\Core\Request::escape($small['title']) ?>
                                    </a>
                                </h4>
                                <span class="small-date"><?= date('d M Y', strtotime($small['created_at'])) ?></span>
                            </div>
                        </article>
                    <?php 
                        endforeach; 
                    endif;
                    ?>
                </div>
            </div>
            
        </div>
    </section>
<?php endif; ?>

<!-- Main Grid Layout -->
<div class="main-layout" style="margin-top: <?= ($currentPage === 1 && empty($search) && count($posts) >= 1) ? '10px' : '40px' ?>;">
    
    <!-- Left Column: Article List -->
    <div class="content-area">
        <h2 class="section-title">
            <?php if (!empty($search)): ?>
                Hasil Pencarian: "<?= \App\Core\Request::escape($search) ?>"
            <?php elseif ($currentPage === 1): ?>
                Artikel Lainnya
            <?php else: ?>
                Artikel Terbaru
            <?php endif; ?>
        </h2>

        <?php 
        // If on page 1 of home, slice the posts array to exclude the first 5 posts that are already featured
        $displayPosts = $posts;
        if ($currentPage === 1 && empty($search)) {
            $displayPosts = array_slice($posts, 5);
        }
        ?>

        <?php if (empty($displayPosts)): ?>
            <div style="background-color: var(--color-white); border: 1px solid rgba(18,18,18,0.05); padding: 40px; border-radius: 16px; text-align: center; color: var(--color-text-muted);">
                <p style="font-size: 16px; font-weight: 600;">Belum ada artikel tambahan lainnya.</p>
                <a href="<?= BASE_URL ?>" style="color: var(--color-accent); font-weight: 700; text-decoration: none; display: inline-block; margin-top: 15px;">Kembali ke Beranda</a>
            </div>
        <?php else: ?>
            <div class="classic-posts-list">
                <?php foreach ($displayPosts as $post): ?>
                    <article class="classic-post-item">
                        <div class="classic-post-meta">
                            <span class="classic-cat"><?= \App\Core\Request::escape($post['category_name']) ?></span>
                            <span class="classic-date"><?= date('d M Y', strtotime($post['created_at'])) ?></span>
                            <span class="classic-author">oleh <?= \App\Core\Request::escape($post['author_name']) ?></span>
                        </div>
                        <h3 class="classic-post-title">
                            <a href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($post['slug']) ?>">
                                <?= \App\Core\Request::escape($post['title']) ?>
                            </a>
                        </h3>
                        <div class="classic-post-layout">
                            <?php if (!empty($post['image'])): ?>
                                <div class="classic-img-wrapper">
                                    <img src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($post['image']) ?>" alt="<?= \App\Core\Request::escape($post['title']) ?>">
                                </div>
                            <?php endif; ?>
                            <div class="classic-text-body">
                                <div class="classic-post-excerpt">
                                    <?= strip_tags($post['content']) ?>
                                </div>
                                <a href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($post['slug']) ?>" class="classic-readmore">
                                    Baca Selengkapnya
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Dynamic Pagination widget -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <!-- Prev page link -->
                    <a href="<?= BASE_URL ?>?q=<?= urlencode($search) ?>&page=<?= $currentPage - 1 ?>" class="pagination-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                        Sebelumnya
                    </a>
                    
                    <!-- Page Numbers list -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="<?= BASE_URL ?>?q=<?= urlencode($search) ?>&page=<?= $i ?>" class="pagination-item <?= ($i === $currentPage) ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <!-- Next page link -->
                    <a href="<?= BASE_URL ?>?q=<?= urlencode($search) ?>&page=<?= $currentPage + 1 ?>" class="pagination-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                        Berikutnya
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Right Column: Sidebar -->
    <aside class="sidebar" style="height: auto; border: none; padding: 0; background: transparent;">
        <!-- Categories Widget -->
        <div class="widget">
            <h4 class="widget-title">Kategori</h4>
            <ul class="category-list">
                <?php foreach ($categories as $cat): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/category/<?= \App\Core\Request::escape($cat['slug']) ?>">
                            <?= \App\Core\Request::escape($cat['name']) ?>
                            <span class="cat-count"><?= $cat['post_count'] ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Recent Posts Widget -->
        <div class="widget">
            <h4 class="widget-title">Artikel Terbaru</h4>
            <div class="recent-posts">
                <?php foreach ($recentPosts as $recent): ?>
                    <div class="recent-item">
                        <h5 class="recent-item-title">
                            <a href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($recent['slug']) ?>">
                                <?= \App\Core\Request::escape($recent['title']) ?>
                            </a>
                        </h5>
                        <span class="recent-item-date"><?= date('d F Y', strtotime($recent['created_at'])) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </aside>

</div>

<?php require_once __DIR__ . '/../layouts/frontend_footer.php'; ?>

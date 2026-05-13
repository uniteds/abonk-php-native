<?php require_once __DIR__ . '/../layouts/frontend_header.php'; ?>

<!-- Hero Section with Custom Theme Pattern -->
<section class="hero">
    <div class="hero-pattern"></div>
    <div class="hero-content">
        <span class="article-cat" style="background-color: rgba(0, 168, 107, 0.15); color: var(--color-accent); font-weight: 800; padding: 6px 16px; border-radius: 50px;">
            Filter Kategori
        </span>
        <h1 style="margin-top: 15px; margin-bottom: 10px;"><?= \App\Core\Request::escape($category['name']) ?></h1>
        <p style="margin-bottom: 0; opacity: 0.8;"><?= \App\Core\Request::escape($category['description'] ?? 'Eksplorasi artikel di bawah kategori ini.') ?></p>
    </div>
</section>

<!-- Main Grid Layout -->
<div class="main-layout">
    
    <!-- Left Column: Article List in Category -->
    <div class="content-area">
        <h2 class="section-title">Artikel Terkait</h2>

        <?php if (empty($posts)): ?>
            <div style="background-color: var(--color-white); border: 1px solid rgba(18,18,18,0.05); padding: 40px; border-radius: 16px; text-align: center; color: var(--color-text-muted);">
                <p style="font-size: 16px; font-weight: 600;">Belum ada artikel dalam kategori ini.</p>
                <a href="<?= BASE_URL ?>" style="color: var(--color-accent); font-weight: 700; text-decoration: none; display: inline-block; margin-top: 15px;">Kembali ke Beranda</a>
            </div>
        <?php else: ?>
            <div class="classic-posts-list">
                <?php foreach ($posts as $post): ?>
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

            <!-- Pagination widget for category -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <a href="<?= BASE_URL ?>/category/<?= $category['slug'] ?>?page=<?= $currentPage - 1 ?>" class="pagination-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                        Sebelumnya
                    </a>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="<?= BASE_URL ?>/category/<?= $category['slug'] ?>?page=<?= $i ?>" class="pagination-item <?= ($i === $currentPage) ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <a href="<?= BASE_URL ?>/category/<?= $category['slug'] ?>?page=<?= $currentPage + 1 ?>" class="pagination-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                        Berikutnya
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Right Column: Sidebar -->
    <aside class="sidebar" style="height: auto; border: none; padding: 0; background: transparent;">
        <!-- Search Widget -->
        <div class="widget">
            <h4 class="widget-title">Pencarian</h4>
            <form action="<?= BASE_URL ?>" method="GET" class="search-form" style="padding: 4px; box-shadow: none; border-color: rgba(18,18,18,0.08); background-color: var(--color-cream)">
                <input type="text" name="q" class="search-input" placeholder="Cari..." style="color: var(--color-dark); padding: 8px 12px; font-size: 14px;">
                <button type="submit" class="search-btn" style="padding: 0 16px; font-size: 13px;">Cari</button>
            </form>
        </div>

        <!-- Categories Widget -->
        <div class="widget">
            <h4 class="widget-title">Kategori</h4>
            <ul class="category-list">
                <?php foreach ($categories as $cat): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/category/<?= \App\Core\Request::escape($cat['slug']) ?>" class="<?= ($cat['id'] === $category['id']) ? 'active' : '' ?>" style="<?= ($cat['id'] === $category['id']) ? 'color: var(--color-accent);' : '' ?>">
                            <?= \App\Core\Request::escape($cat['name']) ?>
                            <span class="cat-count" style="<?= ($cat['id'] === $category['id']) ? 'background-color: var(--color-accent); color: var(--color-dark);' : '' ?>"><?= $cat['post_count'] ?></span>
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

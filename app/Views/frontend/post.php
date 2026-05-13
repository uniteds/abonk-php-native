<?php require_once __DIR__ . '/../layouts/frontend_header.php'; ?>

<!-- Main Grid Layout -->
<div class="main-layout" style="margin-top: 40px;">
    
    <!-- Left Column: Full Article View -->
    <div class="content-area">
        <article class="article-container">
            <header class="article-header">
                <a href="<?= BASE_URL ?>/category/<?= \App\Core\Request::escape($post['category_slug'] ?? 'umum') ?>" class="article-cat">
                    <?= \App\Core\Request::escape($post['category_name']) ?>
                </a>
                <h1 class="article-title"><?= \App\Core\Request::escape($post['title']) ?></h1>
                
                <div class="article-meta">
                    <span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Penulis: <strong><?= \App\Core\Request::escape($post['author_name']) ?></strong>
                    </span>
                    <span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Dipublikasikan: <strong><?= date('d M Y', strtotime($post['created_at'])) ?></strong>
                    </span>
                </div>
            </header>

            <?php if (!empty($post['image'])): ?>
                <img class="article-img" src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($post['image']) ?>" alt="<?= \App\Core\Request::escape($post['title']) ?>">
            <?php endif; ?>

            <!-- Article Body Content (Unescaped as it supports rich content/HTML formatting from Admin) -->
            <div class="article-body">
                <?= $post['content'] ?>
            </div>

            <div style="margin-top: 50px; padding-top: 25px; border-top: 1px solid rgba(18,18,18,0.05);">
                <a href="<?= BASE_URL ?>" class="btn-primary" style="font-size: 14px; text-decoration: none;">
                    &larr; Kembali ke Beranda
                </a>
            </div>
        </article>
    </div>

    <!-- Right Column: Sidebar -->
    <aside class="sidebar" style="height: auto; border: none; padding: 0; background: transparent;">
        <!-- Search widget -->
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

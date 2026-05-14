<?php
// Prepare default SEO variables if not set by controller
$pageTitle = isset($title) ? \App\Core\Request::escape($title) . " - " . \App\Core\Request::escape($settings['site_name'] ?? 'Abonk CMS') : \App\Core\Request::escape($settings['site_name'] ?? 'Abonk CMS');
$metaDesc = isset($metaDescription) ? \App\Core\Request::escape($metaDescription) : \App\Core\Request::escape($settings['site_desc'] ?? 'Sistem Manajemen Konten Native PHP OOP');
$ogImg = isset($ogImage) ? \App\Core\Request::escape($ogImage) : BASE_URL . '/assets/uploads/logo_6a054cd68a43b.webp';
$ogTyp = isset($ogType) ? \App\Core\Request::escape($ogType) : 'website';
$canonUrl = isset($canonicalUrl) ? \App\Core\Request::escape($canonicalUrl) : BASE_URL . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html class="no-js" lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= $pageTitle ?></title>
    <meta name="description" content="<?= $metaDesc ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="<?= $canonUrl ?>">

    <!-- Open Graph (OG) Tags -->
    <meta property="og:title" content="<?= $pageTitle ?>">
    <meta property="og:description" content="<?= $metaDesc ?>">
    <meta property="og:image" content="<?= $ogImg ?>">
    <meta property="og:type" content="<?= $ogTyp ?>">
    <meta property="og:url" content="<?= $canonUrl ?>">
    <meta property="og:site_name" content="<?= \App\Core\Request::escape($settings['site_name'] ?? 'Abonk CMS') ?>">

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $pageTitle ?>">
    <meta name="twitter:description" content="<?= $metaDesc ?>">
    <meta name="twitter:image" content="<?= $ogImg ?>">

    <link rel="shortcut icon" type="image/webp" href="<?= BASE_URL ?>/assets/imgs/theme/favicon.webp">

    <!-- Preconnect Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Crimson+Text:ital,wght@0,400;0,700;1,400&family=Noto+Sans+JP:wght@400;500;700;900&display=swap">

    <!-- Vendor CSS Critical (Direct Load) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/vendor/elegant-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/vendor/slick.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/vendor/slicknav.css">

    <!-- Vendor CSS Non-Critical / Animations (Deferred Load for PageSpeed 100/100) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/vendor/animate.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/vendor/owl.carousel.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/vendor/ticker-style.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/vendor/nice-select.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/vendor/perfect-scrollbar.css" media="print" onload="this.media='all'">

    <!-- Stories Premium Main CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/widgets.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dark.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/responsive.css">
</head>

<body class="theme-mode">
    <div class="scroll-progress primary-bg"></div>
    <!-- Start Preloader -->
    <div class="preloader text-center">
        <div class="circle"></div>
    </div>
    
    <!--Offcanvas sidebar-->
    <aside id="sidebar-wrapper" class="custom-scrollbar offcanvas-sidebar">
        <button class="off-canvas-close" aria-label="Tutup navigasi"><i class="elegant-icon icon_close"></i></button>
        <div class="sidebar-inner">
            <!--Categories-->
            <div class="sidebar-widget widget_categories mb-50 mt-30">
                <div class="widget-header-2 position-relative">
                    <h5 class="mt-5 mb-15">Topik Kategori</h5>
                </div>
                <div class="widget_nav_menu">
                    <ul>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $i => $cat): ?>
                                <li class="cat-item cat-item-<?= ($i % 5) + 2 ?>">
                                    <a href="<?= BASE_URL ?>/category/<?= \App\Core\Request::escape($cat['slug']) ?>"><?= \App\Core\Request::escape($cat['name']) ?></a> 
                                    <span class="post-count"><?= $cat['post_count'] ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            
            <!--Latest-->
            <div class="sidebar-widget widget-latest-posts mb-50">
                <div class="widget-header-2 position-relative mb-30">
                    <h5 class="mt-5 mb-30">Artikel Terkini</h5>
                </div>
                <div class="post-block-list post-module-1 post-module-5">
                    <ul class="list-post">
                        <?php if (!empty($recentPosts)): ?>
                            <?php foreach ($recentPosts as $recent): ?>
                                <li class="mb-30">
                                    <div class="d-flex hover-up-2 transition-normal">
                                        <div class="post-thumb post-thumb-80 d-flex mr-15 border-radius-5 img-hover-scale overflow-hidden">
                                            <a class="color-white" href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($recent['slug']) ?>">
                                                <?php if (!empty($recent['image'])): ?>
                                                    <img src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($recent['image']) ?>" alt="<?= \App\Core\Request::escape($recent['title']) ?>">
                                                <?php else: ?>
                                                    <img src="<?= BASE_URL ?>/assets/imgs/news/thumb-1.jpg" alt="Thumb">
                                                <?php endif; ?>
                                            </a>
                                        </div>
                                        <div class="post-content media-body">
                                            <h6 class="post-title mb-15 text-limit-2-row font-medium"><a href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($recent['slug']) ?>"><?= \App\Core\Request::escape($recent['title']) ?></a></h6>
                                            <div class="entry-meta meta-1 float-start font-x-small text-uppercase">
                                                <span class="post-on"><?= date('d M', strtotime($recent['created_at'])) ?></span>
                                                <span class="post-by has-dot"><?= \App\Core\Request::escape($recent['category_name'] ?? '') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            
            <!--Ads-->
            <div class="sidebar-widget widget-ads">
                <div class="widget-header-2 position-relative mb-30">
                    <h5 class="mt-5 mb-30">Sponsor</h5>
                </div>
                <a href="<?= !empty($settings['sponsor_url']) ? \App\Core\Request::escape($settings['sponsor_url']) : 'https://css.web.id' ?>" target="_blank" aria-label="Kunjungi tautan sponsor kami">
                    <img class="advertise-img border-radius-5" src="<?= !empty($settings['sponsor_image']) ? BASE_URL . '/assets/uploads/' . \App\Core\Request::escape($settings['sponsor_image']) : BASE_URL . '/assets/imgs/ads/ads-1.jpg' ?>" alt="Banner Sponsor">
                </a>
            </div>
        </div>
    </aside>

    <!-- Start Header -->
    <header class="main-header header-style-1 font-heading">
        <div class="header-top">
            <div class="container">
                <div class="row pt-20 pb-20 align-items-center">
                    <div class="col-md-3 col-6">
                        <a href="<?= BASE_URL ?>">
                            <?php if (!empty($settings['site_logo'])): ?>
                                <img class="logo" src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($settings['site_logo']) ?>" alt="<?= \App\Core\Request::escape($settings['site_name'] ?? 'Logo') ?>" style="max-height: 45px; width: auto;">
                            <?php else: ?>
                                <span style="font-size: 26px; font-weight: 800; color: #00A86B;">//</span> <span style="font-size: 22px; font-weight: 800; color: #051f14;"><?= \App\Core\Request::escape($settings['site_name'] ?? 'Abonk CMS') ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="col-md-9 col-6 text-end header-top-right">
                        <ul class="list-inline nav-topbar d-none d-md-inline">
                            <li class="list-inline-item"><a href="<?= BASE_URL ?>/page/tentang-kami"><i class="elegant-icon icon_document_alt mr-5"></i>Tentang Kami</a></li>
                        </ul>
                        <span class="vertical-divider mr-20 ml-20 d-none d-md-inline"></span>
                        <button class="search-icon d-none d-md-inline" aria-label="Buka form pencarian"><span class="mr-15 text-muted font-small"><i class="elegant-icon icon_search mr-5"></i>Pencarian</span></button>
                        <div class="dark-light-mode-cover">
                            <a class="dark-light-mode" href="#" aria-label="Ganti mode gelap/terang"></a>
                        </div>
                        <?php if (\App\Core\Session::isLoggedIn()): ?>
                            <a class="btn btn-radius bg-primary text-white ml-15 font-small box-shadow" href="<?= BASE_URL ?>/admin">Panel Admin</a>
                        <?php else: ?>
                            <a class="btn btn-radius bg-primary text-white ml-15 font-small box-shadow" href="<?= BASE_URL ?>/admin/login">Masuk</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-sticky">
            <div class="container align-self-center position-relative">
                <div class="mobile_menu d-lg-none d-block"></div>
                <div class="main-nav d-none d-lg-block float-start">
                    <nav>
                        <!--Desktop menu-->
                        <ul class="main-menu d-none d-lg-inline font-small">
                            <li><a href="<?= BASE_URL ?>"> <i class="elegant-icon icon_house_alt mr-5"></i> Beranda</a></li>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <li><a href="<?= BASE_URL ?>/category/<?= \App\Core\Request::escape($cat['slug']) ?>"><?= \App\Core\Request::escape($cat['name']) ?></a></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if (!empty($menus)): ?>
                                <?php foreach ($menus as $m): ?>
                                    <li><a href="<?= strpos($m['url'], 'http') === 0 ? \App\Core\Request::escape($m['url']) : BASE_URL . \App\Core\Request::escape($m['url']) ?>"><?= \App\Core\Request::escape($m['label']) ?></a></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                        <!--Mobile menu-->
                        <ul id="mobile-menu" class="d-block d-lg-none text-muted">
                            <li><a href="<?= BASE_URL ?>">Beranda</a></li>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <li><a href="<?= BASE_URL ?>/category/<?= \App\Core\Request::escape($cat['slug']) ?>"><?= \App\Core\Request::escape($cat['name']) ?></a></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if (!empty($menus)): ?>
                                <?php foreach ($menus as $m): ?>
                                    <li><a href="<?= strpos($m['url'], 'http') === 0 ? \App\Core\Request::escape($m['url']) : BASE_URL . \App\Core\Request::escape($m['url']) ?>"><?= \App\Core\Request::escape($m['label']) ?></a></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <div class="float-end header-tools text-muted font-small">
                    <ul class="header-social-network d-inline-block list-inline mr-15">
                        <li class="list-inline-item"><a class="social-icon fb text-xs-center" target="_blank" href="#"><i class="elegant-icon social_facebook"></i></a></li>
                        <li class="list-inline-item"><a class="social-icon tw text-xs-center" target="_blank" href="#"><i class="elegant-icon social_twitter "></i></a></li>
                        <li class="list-inline-item"><a class="social-icon pt text-xs-center" target="_blank" href="#"><i class="elegant-icon social_pinterest "></i></a></li>
                    </ul>
                    <div class="off-canvas-toggle-cover d-inline-block">
                        <div class="off-canvas-toggle hidden d-inline-block" id="off-canvas-toggle" role="button" aria-label="Buka navigasi samping" tabindex="0">
                            <span></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </header>

    <!--Start search form-->
    <div class="main-search-form">
        <div class="container">
            <div class=" pt-50 pb-50 ">
                <div class="row mb-20">
                    <div class="col-12 align-self-center main-search-form-cover m-auto">
                        <p class="text-center"><span class="search-text-bg">Pencarian</span></p>
                        <form action="<?= BASE_URL ?>" method="GET" class="search-header">
                            <div class="input-group w-100">
                                <input type="text" name="q" class="form-control" placeholder="Cari artikel, topik, atau kata kunci menarik...">
                                <div class="input-group-append">
                                    <button class="btn btn-search bg-white" type="submit" aria-label="Mulai pencarian">
                                        <i class="elegant-icon icon_search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

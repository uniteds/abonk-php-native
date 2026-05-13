<?php 
$title = "Kelola Pos Artikel";
$activePage = "posts";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Daftar Pos Artikel</h1>
        <p>Kelola seluruh artikel blog Anda di sini.</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/posts/create" class="btn">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
        Tulis Artikel Baru
    </a>
</div>

<?php if (empty($posts)): ?>
    <div style="background-color: var(--color-card); border: 1px solid var(--color-border); padding: 50px; border-radius: 16px; text-align: center;">
        <p style="color: var(--color-dark); opacity: 0.6; font-size: 15px; margin-bottom: 20px;">Belum ada artikel yang ditulis.</p>
        <a href="<?= BASE_URL ?>/admin/posts/create" class="btn">Mulai Tulis Artikel Pertama</a>
    </div>
<?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">Gambar</th>
                    <th>Judul Artikel</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th style="width: 120px;">Status</th>
                    <th style="width: 150px;">Tanggal Dibuat</th>
                    <th style="width: 180px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td>
                            <?php if (!empty($post['image'])): ?>
                                <img src="<?= BASE_URL ?>/assets/uploads/<?= \App\Core\Request::escape($post['image']) ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid var(--color-border);">
                            <?php else: ?>
                                <div style="width: 50px; height: 50px; background-color: rgba(5, 31, 20, 0.05); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 11px; color: rgba(5, 31, 20, 0.4); border: 1px solid var(--color-border); font-weight: 700; text-transform: uppercase;">
                                    <?= substr(\App\Core\Request::escape($post['category_name']), 0, 2) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong style="font-size: 15px; display: block; margin-bottom: 4px;">
                                <?= \App\Core\Request::escape($post['title']) ?>
                            </strong>
                            <span style="font-size: 12px; opacity: 0.5;">
                                /<?= \App\Core\Request::escape($post['slug']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-info"><?= \App\Core\Request::escape($post['category_name']) ?></span>
                        </td>
                        <td>
                            <?= \App\Core\Request::escape($post['author_name']) ?>
                        </td>
                        <td>
                            <?php if ($post['status'] === 'published'): ?>
                                <span class="badge badge-success">Terbit</span>
                            <?php else: ?>
                                <span class="badge badge-warning" style="background-color: rgba(255, 170, 0, 0.1); color: #ffaa00;">Draf</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size: 13px; opacity: 0.6;">
                            <?= date('d M Y, H:i', strtotime($post['created_at'])) ?>
                        </td>
                        <td>
                            <div class="btn-group" style="justify-content: flex-end;">
                                <?php if ($post['status'] === 'published'): ?>
                                    <a href="<?= BASE_URL ?>/post/<?= \App\Core\Request::escape($post['slug']) ?>" target="_blank" class="btn btn-sm btn-secondary" title="Pratinjau Artikel">
                                        Lihat
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (\App\Core\Session::hasRole('admin') || $post['user_id'] == \App\Core\Session::get('user_id')): ?>
                                    <a href="<?= BASE_URL ?>/admin/posts/edit/<?= $post['id'] ?>" class="btn btn-sm">
                                        Edit
                                    </a>
                                    <a href="<?= BASE_URL ?>/admin/posts/delete/<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan.');">
                                        Hapus
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

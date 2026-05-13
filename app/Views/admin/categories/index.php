<?php 
$title = "Kelola Kategori";
$activePage = "categories";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Daftar Kategori Artikel</h1>
        <p>Klasifikasikan artikel Anda agar pembaca lebih mudah menavigasi konten.</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/categories/create" class="btn">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
        Tambah Kategori Baru
    </a>
</div>

<?php if (empty($categories)): ?>
    <div style="background-color: var(--color-card); border: 1px solid var(--color-border); padding: 50px; border-radius: 16px; text-align: center;">
        <p style="color: var(--color-dark); opacity: 0.6; font-size: 15px; margin-bottom: 20px;">Belum ada kategori.</p>
        <a href="<?= BASE_URL ?>/admin/categories/create" class="btn">Buat Kategori Pertama</a>
    </div>
<?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Nama Kategori</th>
                    <th>Slug URL</th>
                    <th>Deskripsi Singkat</th>
                    <th style="width: 150px; text-align: center;">Jumlah Pos Terkait</th>
                    <th style="width: 180px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td style="font-size: 13px; opacity: 0.5;">
                            #<?= $cat['id'] ?>
                        </td>
                        <td>
                            <strong style="font-size: 15px;">
                                <?= \App\Core\Request::escape($cat['name']) ?>
                            </strong>
                        </td>
                        <td>
                            <span style="font-family: monospace; color: var(--color-accent); font-size: 13px;">
                                /category/<?= \App\Core\Request::escape($cat['slug']) ?>
                            </span>
                        </td>
                        <td style="font-size: 13px; opacity: 0.7; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?= \App\Core\Request::escape($cat['description'] ?? '(Tidak ada deskripsi)') ?>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge badge-info" style="font-size: 13px; padding: 4px 12px;">
                                <?= $cat['post_count'] ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" style="justify-content: flex-end;">
                                <a href="<?= BASE_URL ?>/admin/categories/edit/<?= $cat['id'] ?>" class="btn btn-sm">
                                    Edit
                                </a>
                                <?php if ($cat['slug'] !== 'umum'): ?>
                                    <a href="<?= BASE_URL ?>/admin/categories/delete/<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Jika ada artikel aktif menggunakan kategori ini, sistem akan menolak untuk menghindari kerusakan tautan data.');">
                                        Hapus
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-secondary" style="opacity: 0.4; cursor: not-allowed;" disabled title="Kategori bawaan sistem tidak dapat dihapus">
                                        Hapus
                                    </button>
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

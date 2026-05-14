<?php 
$title = "Kelola Halaman Statis";
$activePage = "pages";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Daftar Halaman Statis</h1>
        <p>Kelola halaman informasi mandiri seperti Tentang Kami, Kontak, atau Kebijakan Privasi.</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/pages/create" class="btn">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
        Tambah Halaman Baru
    </a>
</div>

<?php if (empty($pages)): ?>
    <div style="background-color: var(--color-card); border: 1px solid var(--color-border); padding: 50px; border-radius: 16px; text-align: center;">
        <p style="color: var(--color-dark); opacity: 0.6; font-size: 15px; margin-bottom: 20px;">Belum ada halaman statis.</p>
        <a href="<?= BASE_URL ?>/admin/pages/create" class="btn">Buat Halaman Pertama</a>
    </div>
<?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Judul Halaman</th>
                    <th>URL Tautan</th>
                    <th>Status</th>
                    <th>Tanggal Dibuat</th>
                    <th style="width: 180px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $p): ?>
                    <tr>
                        <td style="font-size: 13px; opacity: 0.5;">
                            #<?= $p['id'] ?>
                        </td>
                        <td>
                            <strong style="font-size: 15px;">
                                <?= \App\Core\Request::escape($p['title']) ?>
                            </strong>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/page/<?= \App\Core\Request::escape($p['slug']) ?>" target="_blank" style="font-family: monospace; color: var(--color-accent); font-size: 13px; text-decoration: none;">
                                /page/<?= \App\Core\Request::escape($p['slug']) ?> ↗
                            </a>
                        </td>
                        <td>
                            <?php if ($p['status'] === 'published'): ?>
                                <span class="badge badge-success">Dipublikasikan</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Draf</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size: 13px; opacity: 0.7;">
                            <?= date('d M Y, H:i', strtotime($p['created_at'])) ?>
                        </td>
                        <td>
                            <div class="btn-group" style="justify-content: flex-end;">
                                <a href="<?= BASE_URL ?>/admin/pages/edit/<?= $p['id'] ?>" class="btn btn-sm">
                                    Edit
                                </a>
                                <a href="<?= BASE_URL ?>/admin/pages/delete/<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus halaman statis ini?');">
                                    Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

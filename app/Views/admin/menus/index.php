<?php 
$title = "Kelola Navigasi Menu";
$activePage = "menus";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Daftar Menu Navigasi</h1>
        <p>Atur item menu navigasi yang muncul di bagian atas halaman publik (Header).</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/menus/create" class="btn">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
        Tambah Navigasi Baru
    </a>
</div>

<?php if (empty($menus)): ?>
    <div style="background-color: var(--color-card); border: 1px solid var(--color-border); padding: 50px; border-radius: 16px; text-align: center;">
        <p style="color: var(--color-dark); opacity: 0.6; font-size: 15px; margin-bottom: 20px;">Belum ada menu navigasi kustom. Sistem akan menggunakan menu bawaan.</p>
        <a href="<?= BASE_URL ?>/admin/menus/create" class="btn">Buat Menu Navigasi Pertama</a>
    </div>
<?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">Urutan</th>
                    <th>Label Navigasi</th>
                    <th>Tautan URL</th>
                    <th style="width: 180px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menus as $menu): ?>
                    <tr>
                        <td style="font-size: 13px; opacity: 0.5; font-weight: bold; text-align: center;">
                            #<?= $menu['order_num'] ?>
                        </td>
                        <td>
                            <strong style="font-size: 15px;">
                                <?= \App\Core\Request::escape($menu['label']) ?>
                            </strong>
                        </td>
                        <td>
                            <span style="font-family: monospace; color: var(--color-accent); font-size: 13px;">
                                <?= \App\Core\Request::escape($menu['url']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" style="justify-content: flex-end;">
                                <a href="<?= BASE_URL ?>/admin/menus/edit/<?= $menu['id'] ?>" class="btn btn-sm">
                                    Edit
                                </a>
                                <a href="<?= BASE_URL ?>/admin/menus/delete/<?= $menu['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus item menu ini?');">
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

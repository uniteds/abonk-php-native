<?php 
$title = "Kelola Pengguna";
$activePage = "users";
require_once __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="admin-header">
    <div>
        <h1>Daftar Pengguna CMS</h1>
        <p>Kelola hak akses kontributor, editor, dan administrator di CMS Anda.</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/users/create" class="btn">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
        Tambah Pengguna Baru
    </a>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th style="width: 80px;">ID</th>
                <th>Username</th>
                <th>Email Akun</th>
                <th style="width: 150px;">Peran (Role)</th>
                <th style="width: 200px;">Tanggal Terdaftar</th>
                <th style="width: 180px; text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $usr): ?>
                <tr>
                    <td style="font-size: 13px; opacity: 0.5;">
                        #<?= $usr['id'] ?>
                    </td>
                    <td>
                        <strong style="font-size: 15px; display: flex; align-items: center; gap: 6px;">
                            <?= \App\Core\Request::escape($usr['username']) ?>
                            <?php if ($usr['id'] == \App\Core\Session::get('user_id')): ?>
                                <span class="badge" style="background-color: rgba(0, 168, 107, 0.15); color: var(--color-accent); font-size: 10px; padding: 2px 6px;">Anda</span>
                            <?php endif; ?>
                        </strong>
                    </td>
                    <td>
                        <?= \App\Core\Request::escape($usr['email']) ?>
                    </td>
                    <td>
                        <?php if ($usr['role'] === 'admin'): ?>
                            <span class="badge" style="background-color: rgba(0, 168, 107, 0.15); color: var(--color-accent);">Administrator</span>
                        <?php else: ?>
                            <span class="badge badge-info">Editor</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size: 13px; opacity: 0.6;">
                        <?= date('d M Y, H:i', strtotime($usr['created_at'])) ?>
                    </td>
                    <td>
                        <div class="btn-group" style="justify-content: flex-end;">
                            <a href="<?= BASE_URL ?>/admin/users/edit/<?= $usr['id'] ?>" class="btn btn-sm">
                                Edit
                            </a>
                            <?php if ($usr['id'] != \App\Core\Session::get('user_id')): ?>
                                <a href="<?= BASE_URL ?>/admin/users/delete/<?= $usr['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna \'<?= \App\Core\Request::escape($usr['username']) ?>\'? Tindakan ini tidak dapat dibatalkan.');">
                                    Hapus
                                </a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-secondary" style="opacity: 0.4; cursor: not-allowed;" disabled title="Anda tidak dapat menghapus akun Anda sendiri">
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

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>

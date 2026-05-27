<?php
$notif = '';

// ==========================================
// ALERT
// ==========================================
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'tambah') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data user berhasil ditambahkan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    if ($_GET['success'] == 'update') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data user berhasil diupdate.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    if ($_GET['success'] == 'hapus') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data user berhasil dihapus.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }
}

// ==========================================
// TAMBAH USER
// ==========================================
if (isset($_POST['tambah_user'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $email = $username . '@gmail.com';
    $password = md5($_POST['password']);
    $role = $_POST['role'];

    $cek = mysqli_query(
        $koneksi,
        "SELECT * FROM users
         WHERE username = '$username'",
    );

    if (mysqli_num_rows($cek) > 0) {
        $notif = '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Username sudah digunakan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    } else {
        mysqli_query(
            $koneksi,
            "INSERT INTO users
            (
                nama_lengkap,
                username,
                email,
                password,
                role
            )
            VALUES
            (
                '$nama_lengkap',
                '$username',
                '$email',
                '$password',
                '$role'
            )",
        );

        echo "
        <script>
            window.location='index.php?page=user&success=tambah';
        </script>
        ";

        exit();
    }
}

// ==========================================
// UPDATE USER
// ==========================================
if (isset($_POST['update_user'])) {
    $id_user = $_POST['id_user'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);

        mysqli_query(
            $koneksi,
            "UPDATE users SET
                nama_lengkap = '$nama_lengkap',
                username = '$username',
                password = '$password',
                role = '$role'
            WHERE id_user = '$id_user'",
        );
    } else {
        mysqli_query(
            $koneksi,
            "UPDATE users SET
                nama_lengkap = '$nama_lengkap',
                username = '$username',
                role = '$role'
            WHERE id_user = '$id_user'",
        );
    }

    echo "
    <script>
        window.location='index.php?page=user&success=update';
    </script>
    ";

    exit();
}

// ==========================================
// HAPUS USER
// ==========================================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query(
        $koneksi,
        "DELETE FROM users
         WHERE id_user = '$id'",
    );

    echo "
    <script>
        window.location='index.php?page=user&success=hapus';
    </script>
    ";

    exit();
}

// Ambil data user
$query = 'SELECT * FROM users ORDER BY id_user DESC';
$result = mysqli_query($koneksi, $query);
?>

<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                User Management
            </h4>

            <small class="text-muted">
                Kelola akun pengguna sistem inventory.
            </small>
        </div>

        <button class="btn btn-primary px-4 py-2 shadow-sm rounded-4" data-bs-toggle="modal"
            data-bs-target="#modalTambahUser">

            <i class="fa-solid fa-plus me-1"></i>
            Tambah User

        </button>

    </div>

    <?= $notif ?>

    <div class="card border-0 user-card">
        <div class="card-body">
            <table id="tableUser" class="table align-middle table-hover nowrap dt-responsive w-100 user-table">
                <thead class="table-light">

                    <tr>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>Level</th>
                        <th width="15%">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    $data_user = [];

                    while ($data = mysqli_fetch_assoc($result)) :
                    ?>

                    <tr>
                        <td>
                            <span class="user-number">

                                <?= $no++ ?>

                            </span>
                        </td>

                        <td>

                            <div class="d-flex align-items-center">

                                <div class="user-avatar">

                                    <?= strtoupper(substr($data['nama_lengkap'], 0, 1)) ?>

                                </div>

                                <div class="ms-3">

                                    <div class="fw-semibold text-dark">

                                        <?= $data['nama_lengkap'] ?>

                                    </div>

                                    <small class="text-muted">

                                        <?= $data['username'] ?>

                                    </small>

                                </div>

                            </div>

                        </td>

                        <td>

                            <?php if ($data['role'] == 'Admin') : ?>

                            <span class="badge-role-admin">

                                <i class="fa-solid fa-crown me-1"></i>

                                Admin

                            </span>

                            <?php else : ?>

                            <span class="badge-role-user">

                                <i class="fa-solid fa-user me-1"></i>

                                Petugas

                            </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editUser<?= $data['id_user'] ?>">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="index.php?page=user&hapus=<?= $data['id_user'] ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus user?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>

                    </tr>
                    <?php $data_user[] = $data; ?>
                    <?php endwhile; ?>

                </tbody>
            </table>

        </div>

    </div>

</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="modalTambahUser" tabindex="-1">

    <div class="modal-dialog">

        <form action="" method="POST" class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Tambah User
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="mb-3">

                    <label class="form-label">
                        Nama Lengkap
                    </label>

                    <input type="text" name="nama_lengkap" class="form-control" required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Username
                    </label>

                    <input type="text" name="username" class="form-control" required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Password
                    </label>

                    <input type="password" name="password" class="form-control" required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Level
                    </label>

                    <select name="role" class="form-select" required>

                        <option value="">
                            -- Pilih Level --
                        </option>

                        <option value="Admin">
                            👑 Admin
                        </option>

                        <option value="Petugas">
                            👩‍💼 Petugas
                        </option>

                    </select>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                    Batal

                </button>

                <button type="submit" name="tambah_user" class="btn btn-primary">

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>

<!-- Modal Edit User -->
<?php foreach ($data_user as $data): ?>
<div class="modal fade" id="editUser<?= $data['id_user'] ?>" tabindex="-1">

    <div class="modal-dialog">

        <form action="" method="POST" class="modal-content">

            <input type="hidden" name="id_user" value="<?= $data['id_user'] ?>">

            <div class="modal-header">

                <h5 class="modal-title">
                    Edit User
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="mb-3">

                    <label class="form-label">
                        Nama Lengkap
                    </label>

                    <input type="text" name="nama_lengkap" class="form-control" value="<?= $data['nama_lengkap'] ?>"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Username
                    </label>

                    <input type="text" name="username" class="form-control" value="<?= $data['username'] ?>"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Password Baru
                    </label>

                    <input type="password" name="password" class="form-control">

                    <small class="text-muted">
                        Kosongkan jika tidak ingin mengganti password.
                    </small>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Level
                    </label>

                    <select name="role" class="form-select" required>

                        <option value="Admin" <?= $data['role'] == 'Admin' ? 'selected' : '' ?>>

                            Admin

                        </option>

                        <option value="Petugas" <?= $data['role'] == 'Petugas' ? 'selected' : '' ?>>

                            Petugas

                        </option>

                    </select>

                </div>

            </div>

            <div class="modal-footer">

                <button type="submit" name="update_user" class="btn btn-primary">

                    Update

                </button>

            </div>

        </form>

    </div>

</div>
<?php endforeach; ?>

<script>
    window.addEventListener('DOMContentLoaded', function() {

        $(document).ready(function() {

            if ($('#tableUser').length) {

                $('#tableUser').DataTable({

                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }

                });

            }

        });

    });
</script>

<style>
    .user-card {

        border-radius: 30px;

        background: rgba(255, 255, 255, .82);

        backdrop-filter: blur(14px);

        box-shadow:
            0 10px 35px rgba(236, 72, 153, .08);

    }

    .user-number {

        width: 36px;

        height: 36px;

        border-radius: 12px;

        background:
            linear-gradient(135deg,
                #ec4899,
                #d946ef);

        display: flex;

        align-items: center;

        justify-content: center;

        color: white;

        font-weight: 600;

        font-size: 13px;

    }

    .user-avatar {

        width: 48px;

        height: 48px;

        border-radius: 16px;

        background:
            linear-gradient(135deg,
                #ec4899,
                #f472b6);

        display: flex;

        align-items: center;

        justify-content: center;

        color: white;

        font-weight: 700;

        font-size: 18px;

        box-shadow:
            0 8px 20px rgba(236, 72, 153, .18);

    }

    .badge-role-admin {

        background: #fef3c7;

        color: #92400e;

        padding: 8px 14px;

        border-radius: 12px;

        font-weight: 600;

        font-size: 13px;

    }

    .badge-role-user {

        background: #dbeafe;

        color: #1d4ed8;

        padding: 8px 14px;

        border-radius: 12px;

        font-weight: 600;

        font-size: 13px;

    }

    .table {

        border-collapse: separate;

        border-spacing: 0 10px;

    }

    .table thead th {

        border: none !important;

        background: transparent !important;

        color: #6b7280;

        font-size: 14px;

        font-weight: 600;

    }

    .table tbody tr {

        background: rgba(255, 255, 255, .9);

        transition: .3s;

        box-shadow:
            0 4px 15px rgba(0, 0, 0, .03);

    }

    .table tbody tr:hover {

        transform: translateY(-2px);

    }

    .table tbody td {

        border: none;

        padding: 16px 14px;

        vertical-align: middle;

    }

    .table tbody td:first-child {

        border-radius: 18px 0 0 18px;

    }

    .table tbody td:last-child {

        border-radius: 0 18px 18px 0;

    }

    .modal-content {

        border: none;

        border-radius: 30px;

        overflow: hidden;

        background: rgba(255, 255, 255, .96);

        backdrop-filter: blur(16px);

        box-shadow:
            0 20px 45px rgba(236, 72, 153, .15);

    }

    .modal-header {

        border-bottom: 1px solid #fce7f3;

        padding: 22px 26px;

    }

    .modal-footer {

        border-top: 1px solid #fce7f3;

        padding: 18px 26px;

    }

    .form-control,
    .form-select {

        border-radius: 14px;

        border: 1px solid #f3e8ff;

        padding: 10px 14px;

    }

    .form-control:focus,
    .form-select:focus {

        border-color: #ec4899;

        box-shadow:
            0 0 0 .15rem rgba(236, 72, 153, .15);

    }

    .dataTables_wrapper .dataTables_filter input {

        border-radius: 12px;

        border: 1px solid #f3e8ff;

        padding: 6px 12px;

    }

    .dataTables_wrapper .dataTables_length select {

        border-radius: 12px;

        border: 1px solid #f3e8ff;

    }
</style>

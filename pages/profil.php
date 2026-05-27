<?php

$id_user = $_SESSION['id_user'];

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM users
     WHERE id_user = '$id_user'",
);

$data = mysqli_fetch_assoc($query);

$notif = '';

// ==========================================
// UPDATE PROFIL
// ==========================================
if (isset($_POST['update_profil'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Jika password diisi
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);

        mysqli_query(
            $koneksi,
            "UPDATE users SET
                nama_lengkap = '$nama_lengkap',
                username = '$username',
                email = '$email',
                password = '$password'
            WHERE id_user = '$id_user'",
        );
    } else {
        mysqli_query(
            $koneksi,
            "UPDATE users SET
                nama_lengkap = '$nama_lengkap',
                username = '$username',
                email = '$email'
            WHERE id_user = '$id_user'",
        );
    }

    $_SESSION['nama_lengkap'] = $nama_lengkap;

    echo "
    <script>
        window.location='index.php?page=profil&success=update';
    </script>
    ";

    exit();
}

// ==========================================
// ALERT
// ==========================================
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'update') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Profil berhasil diupdate.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }
}

?>

<div class="container-fluid p-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>

            <h4 class="fw-bold text-dark mb-1">
                My Profile
            </h4>

            <small class="text-muted">
                Kelola informasi akun dan keamanan profil pengguna.
            </small>

        </div>
        <div>

            <a href="auth/logout.php" class="btn btn-outline-danger px-4 py-2 rounded-4">

                <i class="fa-solid fa-right-from-bracket me-2"></i>

                Logout

            </a>

        </div>

    </div>

    <?= $notif ?>

    <div class="row">

        <div class="col-md-8">

            <div class="card border-0 profil-card">

                <div class="card-body">

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">
                                Nama Lengkap
                            </label>

                            <input type="text" name="nama_lengkap" class="form-control"
                                value="<?= $data['nama_lengkap'] ?>" required>

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
                                Email
                            </label>

                            <input type="email" name="email" class="form-control" value="<?= $data['email'] ?>"
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
                                Role
                            </label>

                            <input type="text"
                                style="background:#fdf2f8; color:#ec4899; font-weight:600;"
                                class="form-control" value="<?= $data['role'] ?>" readonly>

                        </div>

                        <button type="submit" name="update_profil"
                            class="btn btn-primary px-4 py-2 rounded-4 shadow-sm">

                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            Simpan Perubahan

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 profil-side-card">

                <div class="card-body text-center">

                    <div class="mb-3">

                        <div class="profil-avatar mx-auto">

                            <?= strtoupper(substr($data['nama_lengkap'], 0, 1)) ?>

                        </div>

                    </div>

                    <h5 class="fw-bold mb-1">
                        <?= $data['nama_lengkap'] ?>
                    </h5>

                    <p class="text-muted mb-2">
                        <?= $data['role'] ?>
                    </p>

                    <small class="text-muted">
                        Bergabung di sistem inventory.
                    </small>
                    <hr class="my-4">

                    <div class="text-start">

                        <div class="profil-info-item">

                            <span>
                                Username
                            </span>

                            <strong>
                                <?= $data['username'] ?>
                            </strong>

                        </div>

                        <div class="profil-info-item">

                            <span>
                                Email
                            </span>

                            <strong>
                                <?= $data['email'] ?>
                            </strong>

                        </div>

                        <div class="profil-info-item border-0">

                            <span>
                                Role
                            </span>

                            <strong>

                                <?= $data['role'] ?>

                            </strong>

                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<style>
    .profil-card {

        border-radius: 30px;

        background: rgba(255, 255, 255, .82);

        backdrop-filter: blur(14px);

        box-shadow:
            0 10px 35px rgba(236, 72, 153, .08);

    }

    .profil-side-card {

        border-radius: 30px;

        background:
            linear-gradient(135deg,
                #fdf2f8,
                #faf5ff);

        backdrop-filter: blur(14px);

        box-shadow:
            0 10px 35px rgba(236, 72, 153, .08);

    }

    .profil-avatar {

        width: 110px;

        height: 110px;

        border-radius: 30px;

        background:
            linear-gradient(135deg,
                #ec4899,
                #d946ef);

        display: flex;

        align-items: center;

        justify-content: center;

        color: white;

        font-size: 42px;

        font-weight: 700;

        box-shadow:
            0 15px 35px rgba(236, 72, 153, .2);

    }

    .profil-info-item {

        display: flex;

        justify-content: space-between;

        align-items: center;

        padding: 14px 0;

        border-bottom: 1px solid #f3f4f6;

    }

    .profil-info-item span {

        color: #6b7280;

        font-size: 14px;

    }

    .profil-info-item strong {

        color: #111827;

        font-size: 14px;

    }

    .form-control {

        border-radius: 14px;

        border: 1px solid #f3e8ff;

        padding: 12px 14px;

    }

    .form-control:focus {

        border-color: #ec4899;

        box-shadow:
            0 0 0 .15rem rgba(236, 72, 153, .15);

    }

    .btn-primary {

        background:
            linear-gradient(135deg,
                #ec4899,
                #d946ef);

        border: none;

    }

    .btn-outline-danger {

        border-radius: 14px;

    }
</style>

<?php
session_start();
include '../config/koneksi.php';

$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM users
         WHERE username = '$username'
         AND password = '$password'",
    );

    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);

        $_SESSION['login'] = true;
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
        $_SESSION['role'] = $data['role'];

        header('Location:../index.php');
        exit();
    } else {
        $error = '
        <div class="alert alert-danger">
            Username atau password salah.
        </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Login Sistem Inventory
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="bg-light">

    <div class="container">

        <div class="row justify-content-center align-items-center vh-100">

            <div class="col-md-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        <div class="text-center mb-4">

                            <h3 class="fw-bold">
                                Inventory App
                            </h3>

                            <small class="text-muted">
                                Silakan login terlebih dahulu
                            </small>

                        </div>

                        <?= $error ?>

                        <form method="POST">

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

                            <button type="submit" name="login" class="btn btn-primary w-100">

                                Login

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>

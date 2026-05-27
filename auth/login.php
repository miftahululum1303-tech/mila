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
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Username atau password salah.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Login | Inventory App
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            background:
                linear-gradient(135deg,
                    #fdf2f8,
                    #fce7f3,
                    #f5d0fe);
            overflow: hidden;
        }

        .bg-shape-1 {
            position: absolute;
            width: 350px;
            height: 350px;
            background: rgba(255, 255, 255, 0.35);
            border-radius: 50%;
            top: -100px;
            left: -100px;
            filter: blur(10px);
        }

        .bg-shape-2 {
            position: absolute;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            bottom: -80px;
            right: -80px;
            filter: blur(10px);
        }

        .login-card {
            border: none;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(15px);
            box-shadow:
                0 10px 35px rgba(236, 72, 153, 0.15);
            overflow: hidden;
        }

        .login-left {
            background:
                linear-gradient(180deg,
                    #ec4899,
                    #d946ef);
            color: white;
            padding: 50px 35px;
            position: relative;
        }

        .login-left h2 {
            font-weight: 700;
            line-height: 1.4;
        }

        .login-left p {
            opacity: .9;
            font-size: 14px;
        }

        .inventory-icon {
            width: 85px;
            height: 85px;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            margin-bottom: 30px;
        }

        .login-right {
            padding: 45px 40px;
            background: white;
        }

        .login-title {
            font-weight: 700;
            color: #1f2937;
        }

        .login-subtitle {
            color: #6b7280;
            font-size: 14px;
        }

        .form-label {
            font-weight: 500;
            color: #374151;
        }

        .form-control {
            border-radius: 14px;
            padding: 12px 15px;
            border: 1px solid #e5e7eb;
            transition: .3s;
        }

        .form-control:focus {
            border-color: #ec4899;
            box-shadow: 0 0 0 .15rem rgba(236, 72, 153, .15);
        }

        .input-group-text {
            border-radius: 14px 0 0 14px;
            background: #fdf2f8;
            border: 1px solid #e5e7eb;
            color: #ec4899;
        }

        .btn-login {
            background:
                linear-gradient(135deg,
                    #ec4899,
                    #d946ef);
            border: none;
            border-radius: 14px;
            padding: 12px;
            font-weight: 600;
            transition: .3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(217, 70, 239, .25);
        }

        .copyright {
            font-size: 12px;
            color: #9ca3af;
        }

        @media(max-width: 768px) {

            .login-left {
                display: none;
            }

            body {
                overflow: auto;
            }

        }
    </style>

</head>

<body>

    <div class="bg-shape-1"></div>
    <div class="bg-shape-2"></div>

    <div class="container">

        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-lg-9">

                <div class="card login-card">

                    <div class="row g-0">

                        <!-- LEFT -->
                        <div class="col-md-5">

                            <div class="login-left h-100 d-flex flex-column justify-content-center">

                                <div class="inventory-icon">

                                    <i class="fa-solid fa-boxes-stacked"></i>

                                </div>

                                <h2>
                                    Inventory
                                    Management
                                    System
                                </h2>

                                <p class="mt-3 mb-0">

                                    Sistem persediaan barang
                                    sederhana untuk membantu
                                    pengelolaan stok barang.

                                </p>

                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="col-md-7">

                            <div class="login-right">

                                <div class="mb-4">

                                    <h3 class="login-title">
                                        Welcome Back 👋
                                    </h3>

                                    <p class="login-subtitle mb-0">
                                        Silakan login untuk melanjutkan
                                    </p>

                                </div>

                                <?= $error ?>

                                <form method="POST">

                                    <div class="mb-3">

                                        <label class="form-label">
                                            Username
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">

                                                <i class="fa-solid fa-user"></i>

                                            </span>

                                            <input type="text" name="username" class="form-control"
                                                placeholder="Masukkan username" required>

                                        </div>

                                    </div>

                                    <div class="mb-4">

                                        <label class="form-label">
                                            Password
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">

                                                <i class="fa-solid fa-lock"></i>

                                            </span>

                                            <input type="password" name="password" class="form-control"
                                                placeholder="Masukkan password" required>

                                        </div>

                                    </div>

                                    <button type="submit" name="login" class="btn btn-login text-white w-100">

                                        <i class="fa-solid fa-right-to-bracket me-2"></i>
                                        Login

                                    </button>

                                </form>

                                <div class="text-center mt-4">

                                    <span class="copyright">
                                        © <?= date('Y') ?> Inventory App
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

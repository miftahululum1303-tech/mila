<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System - PROLI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <style>
        :root {

            --primary-color: #ec4899;
            --primary-soft: #fdf2f8;
            --dark-color: #1f2937;
            --light-color: #fdfdfd;

            --sidebar-width: 260px;
            --sidebar-collapsed-width: 75px;

            --transition-speed: 0.3s;

        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {

            background:
                linear-gradient(135deg,
                    #fdf2f8,
                    #faf5ff);

            overflow-x: hidden;

            display: flex;
            flex-direction: column;

            min-height: 100vh;

        }

        /* ========================================= */
        /* NAVBAR */
        /* ========================================= */

        .navbar-custom {

            background: rgba(255, 255, 255, 0.85);

            backdrop-filter: blur(15px);

            border-bottom: 1px solid #f3e8ff;

            box-shadow:
                0 4px 20px rgba(236, 72, 153, .08);

            z-index: 1030;

            height: 70px;

        }

        .navbar-brand-box {

            width: var(--sidebar-width);

            transition: width var(--transition-speed) ease;

        }

        body.sidebar-toggled .navbar-brand-box {

            width: var(--sidebar-collapsed-width);

        }

        /* ========================================= */
        /* WRAPPER */
        /* ========================================= */

        .app-wrapper {

            display: flex;

            flex: 1;

            margin-top: 70px;

            position: relative;

        }

        /* ========================================= */
        /* SIDEBAR */
        /* ========================================= */

        .sidebar {

            width: var(--sidebar-width);

            background: rgba(255, 255, 255, 0.75);

            backdrop-filter: blur(18px);

            border-right: 1px solid #f3e8ff;

            transition: width var(--transition-speed) ease;

            position: fixed;

            top: 70px;

            bottom: 0;

            left: 0;

            z-index: 1020;

            overflow-y: auto;

            overflow-x: hidden;

            box-shadow:
                0 4px 25px rgba(236, 72, 153, .05);

        }

        body.sidebar-toggled .sidebar {

            width: var(--sidebar-collapsed-width);

        }

        /* ========================================= */
        /* MAIN CONTENT */
        /* ========================================= */

        .main-content {

            flex: 1;

            margin-left: var(--sidebar-width);

            transition: margin-left var(--transition-speed) ease;

            padding: 25px;

            display: flex;

            flex-direction: column;

        }

        body.sidebar-toggled .main-content {

            margin-left: var(--sidebar-collapsed-width);

        }

        /* ========================================= */
        /* MENU */
        /* ========================================= */

        .nav-menu {

            list-style: none;

            padding: 15px 0;

            margin: 0;

        }

        .nav-item-custom {

            width: var(--sidebar-width);

            transition: width var(--transition-speed) ease;

            padding: 0 12px;

            margin-bottom: 6px;

        }

        body.sidebar-toggled .nav-item-custom {

            width: var(--sidebar-collapsed-width);

        }

        .nav-link-custom {

            display: flex;

            align-items: center;

            padding: 13px 16px;

            color: #6b7280;

            text-decoration: none;

            transition: .3s;

            white-space: nowrap;

            border-radius: 16px;

            font-weight: 500;

        }

        .nav-link-custom:hover {

            background: var(--primary-soft);

            color: var(--primary-color);

        }

        .nav-link-custom.active {

            background:
                linear-gradient(135deg,
                    #ec4899,
                    #d946ef);

            color: white;

            box-shadow:
                0 10px 20px rgba(236, 72, 153, .18);

        }

        .nav-link-custom i {

            width: 24px;

            font-size: 1rem;

            text-align: center;

            margin-right: 15px;

            flex-shrink: 0;

        }

        body.sidebar-toggled .nav-link-custom i {

            margin-right: 0;

        }

        .menu-text {

            transition: opacity var(--transition-speed);

            opacity: 1;

        }

        body.sidebar-toggled .menu-text {

            opacity: 0;

            display: none;

        }

        /* ========================================= */
        /* CARD */
        /* ========================================= */

        .card {

            border: none;

            border-radius: 24px;

            background: rgba(255, 255, 255, 0.75);

            backdrop-filter: blur(14px);

            box-shadow:
                0 8px 30px rgba(236, 72, 153, .08);

        }

        /* ========================================= */
        /* BUTTON */
        /* ========================================= */

        .btn-primary {

            background:
                linear-gradient(135deg,
                    #ec4899,
                    #d946ef);

            border: none;

            border-radius: 12px;

        }

        .btn-primary:hover {

            opacity: .95;

            transform: translateY(-1px);

        }

        /* ========================================= */
        /* TABLE */
        /* ========================================= */

        .table {

            border-radius: 16px;

            overflow: hidden;

        }

        .table thead {

            background: #fdf2f8;

        }

        .table thead th {

            color: #374151;

            font-weight: 600;

            border: none;

        }

        .table tbody td {

            vertical-align: middle;

        }

        /* ========================================= */
        /* PROFILE */
        /* ========================================= */

        .profile-img {

            width: 38px;

            height: 38px;

            object-fit: cover;

            border-radius: 50%;

            border: 2px solid #fbcfe8;

        }

        /* ========================================= */
        /* FOOTER */
        /* ========================================= */

        footer {

            background: rgba(255, 255, 255, 0.8);

            backdrop-filter: blur(12px);

            border-top: 1px solid #f3e8ff;

            height: 55px;

            line-height: 55px;

            margin-left: var(--sidebar-width);

            transition: margin-left var(--transition-speed) ease;

        }

        body.sidebar-toggled footer {

            margin-left: var(--sidebar-collapsed-width);

        }

        /* ========================================= */
        /* MOBILE */
        /* ========================================= */

        @media (max-width: 768px) {

            .sidebar {

                margin-left: calc(-1 * var(--sidebar-width));

            }

            body.sidebar-toggled .sidebar {

                margin-left: 0;

                width: var(--sidebar-width);

            }

            body.sidebar-toggled .menu-text {

                opacity: 1;

                display: block;

            }

            .main-content {

                margin-left: 0 !important;

            }

            footer {

                margin-left: 0 !important;

            }

        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand navbar-light navbar-custom fixed-top p-0">
        <div class="navbar-brand-box d-flex align-items-center justify-content-between px-3">
            <a class="navbar-brand d-flex align-items-center fw-bold text-dark" href="index.php"
                style="color: var(--primary-color) !important;">
                <i class="fa-solid fa-boxes-stacked me-2" style="color: var(--primary-color);"></i>
                <span class="menu-text">PROLI</span>
            </a>
            <button class="btn btn-link text-dark p-0 d-none d-md-block" id="sidebarToggleDesktop">
                <i class="fa-solid fa-bars fa-lg"></i>
            </button>
        </div>

        <button class="btn btn-link text-dark ps-3 d-md-none" id="sidebarToggleMobile">
            <i class="fa-solid fa-bars fa-lg"></i>
        </button>

        <div class="ms-auto pe-3 d-flex align-items-center">
            <div class="dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                    id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-img d-flex align-items-center justify-content-center bg-light">
                        <i class="fa-solid fa-user text-secondary"></i>
                    </div>
                    <span class="d-none d-sm-inline text-dark fw-semibold small"><?= $_SESSION['nama_lengkap'] ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm mt-2" aria-labelledby="userMenu">
                    <li><a class="dropdown-menu-item dropdown-item py-2" href="index.php?page=profil"><i
                                class="fa-solid fa-user me-2 text-muted"></i> Profil Saya</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-menu-item dropdown-item py-2 text-danger" href="auth/logout.php">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>
                            Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="app-wrapper">

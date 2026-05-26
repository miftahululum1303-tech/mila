<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System - PROLI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #3e7ccb;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5; /* Tampilan bersih ala Facebook Modern */
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar Styling */
        .navbar-custom {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            z-index: 1030;
            height: 60px;
        }

        .navbar-brand-box {
            width: var(--sidebar-width);
            transition: width var(--transition-speed) ease;
        }

        body.sidebar-toggled .navbar-brand-box {
            width: var(--sidebar-collapsed-width);
        }

        /* Layout Wrapper */
        .app-wrapper {
            display: flex;
            flex: 1;
            margin-top: 60px; /* Offset Navbar */
            position: relative;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            transition: width var(--transition-speed) ease;
            position: fixed;
            top: 60px;
            bottom: 0;
            left: 0;
            z-index: 1020;
            overflow-y: auto;
            overflow-x: hidden;
        }

        body.sidebar-toggled .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        /* Main Content Styling */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition-speed) ease;
            padding: 24px;
            background-color: #f0f2f5;
            display: flex;
            flex-direction: column;
        }

        body.sidebar-toggled .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Sidebar Menu Items */
        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item-custom {
            width: var(--sidebar-width);
            transition: width var(--transition-speed) ease;
        }

        body.sidebar-toggled .nav-item-custom {
            width: var(--sidebar-collapsed-width);
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
            border-left: 4px solid transparent;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            color: var(--primary-color);
            background-color: #f1f5f9;
            border-left-color: var(--primary-color);
        }

        .nav-link-custom i {
            width: 24px;
            font-size: 1.1rem;
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

        body.sidebar-toggled .menu-text,
        body.sidebar-toggled .arrow-icon {
            opacity: 0;
            pointer-events: none;
            display: none;
        }

        /* Submenu Styling */
        .submenu {
            list-style: none;
            padding-left: 0;
            background-color: #f8fafc;
            display: none;
        }

        .submenu.show {
            display: block;
        }

        .submenu .nav-link-custom {
            padding-left: 54px;
        }

        /* Arrow Rotation */
        .arrow-icon {
            margin-left: auto;
            transition: transform 0.2s ease;
        }

        .nav-link-custom[aria-expanded="true"] .arrow-icon {
            transform: rotate(90deg);
        }

        /* Card Custom (Facebook Modern Style) */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        /* Footer Sticky */
        footer {
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            height: 50px;
            line-height: 50px;
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition-speed) ease;
            z-index: 1010;
        }

        body.sidebar-toggled footer {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Responsive Mobile Breaks */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            body.sidebar-toggled .sidebar {
                margin-left: 0;
                width: var(--sidebar-width);
            }
            body.sidebar-toggled .menu-text,
            body.sidebar-toggled .arrow-icon {
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
            <a class="navbar-brand d-flex align-items-center fw-bold text-dark" href="index.php" style="color: var(--primary-color) !important;">
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
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop" class="rounded-circle me-2" width="32" height="32" alt="User Profile" style="object-fit: cover;">
                    <span class="d-none d-sm-inline text-dark fw-semibold small"><?= $_SESSION['nama_lengkap']; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm mt-2" aria-labelledby="userMenu">
                    <li><a class="dropdown-menu-item dropdown-item py-2" href="index.php?page=profil"><i class="fa-solid fa-user me-2 text-muted"></i> Profil Saya</a></li>
                    <li><hr class="dropdown-divider"></li>
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

<aside class="sidebar" id="sidebarLayout">

    <!-- LOGO -->
    <div class="px-4 pt-4 pb-3">

        <div class="d-flex align-items-center">

            <div class="d-flex align-items-center justify-content-center rounded-4"
                style="
                    width:50px;
                    height:50px;
                    background:linear-gradient(135deg,#ec4899,#d946ef);
                    color:white;
                    font-size:20px;
                    box-shadow:0 8px 20px rgba(236,72,153,.25);
                ">

                <i class="fa-solid fa-boxes-stacked"></i>

            </div>

            <div class="ms-3 menu-text">

                <h6 class="fw-bold mb-0 text-dark">
                    Inventory App
                </h6>

                <small class="text-muted">
                    Management System
                </small>

            </div>

        </div>

    </div>

    <!-- MENU -->
    <ul class="nav-menu px-2">

        <li class="mb-2 px-3 menu-text">

            <small class="text-uppercase text-muted fw-semibold" style="font-size:11px; letter-spacing:1px;">

                Main Menu

            </small>

        </li>

        <!-- DASHBOARD -->
        <li class="nav-item-custom">

            <a href="index.php?page=dashboard" class="nav-link-custom <?php echo !isset($_GET['page']) || $_GET['page'] == 'dashboard' ? 'active' : ''; ?>">

                <i class="fa-solid fa-house"></i>

                <span class="menu-text">
                    Dashboard
                </span>

            </a>

        </li>

        <?php if ($_SESSION['role'] == 'Admin') : ?>

        <!-- DATA BARANG -->
        <li class="nav-item-custom">

            <a href="index.php?page=barang" class="nav-link-custom <?php echo isset($_GET['page']) && $_GET['page'] == 'barang' ? 'active' : ''; ?>">

                <i class="fa-solid fa-box"></i>

                <span class="menu-text">
                    Data Barang
                </span>

            </a>

        </li>

        <!-- SUPPLIER -->
        <li class="nav-item-custom">

            <a href="index.php?page=supplier" class="nav-link-custom <?php echo isset($_GET['page']) && $_GET['page'] == 'supplier' ? 'active' : ''; ?>">

                <i class="fa-solid fa-truck-fast"></i>

                <span class="menu-text">
                    Supplier
                </span>

            </a>

        </li>

        <?php endif; ?>

        <!-- BARANG MASUK -->
        <li class="nav-item-custom">

            <a href="index.php?page=barang-masuk" class="nav-link-custom <?php echo isset($_GET['page']) && $_GET['page'] == 'barang-masuk' ? 'active' : ''; ?>">

                <i class="fa-solid fa-arrow-down"></i>

                <span class="menu-text">
                    Barang Masuk
                </span>

            </a>

        </li>

        <!-- BARANG KELUAR -->
        <li class="nav-item-custom">

            <a href="index.php?page=barang-keluar" class="nav-link-custom <?php echo isset($_GET['page']) && $_GET['page'] == 'barang-keluar' ? 'active' : ''; ?>">

                <i class="fa-solid fa-arrow-up"></i>

                <span class="menu-text">
                    Barang Keluar
                </span>

            </a>

        </li>

        <!-- LAPORAN -->
        <li class="nav-item-custom">

            <a href="index.php?page=laporan" class="nav-link-custom <?php echo isset($_GET['page']) && $_GET['page'] == 'laporan' ? 'active' : ''; ?>">

                <i class="fa-solid fa-chart-pie"></i>

                <span class="menu-text">
                    Laporan
                </span>

            </a>

        </li>

        <?php if ($_SESSION['role'] == 'Admin') : ?>

        <li class="mt-4 mb-2 px-3 menu-text">

            <small class="text-uppercase text-muted fw-semibold" style="font-size:11px; letter-spacing:1px;">

                Settings

            </small>

        </li>

        <!-- USER -->
        <li class="nav-item-custom">

            <a href="index.php?page=user" class="nav-link-custom <?php echo isset($_GET['page']) && $_GET['page'] == 'user' ? 'active' : ''; ?>">

                <i class="fa-solid fa-user-gear"></i>

                <span class="menu-text">
                    User Management
                </span>

            </a>

        </li>

        <?php endif; ?>

    </ul>

    <!-- BOTTOM PROFILE -->
    <div class="mt-auto p-3 menu-text">

        <div class="card border-0 rounded-4"
            style="
                background:linear-gradient(135deg,#fdf2f8,#faf5ff);
            ">

            <div class="card-body p-3">

                <div class="d-flex align-items-center">

                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="
                            width:45px;
                            height:45px;
                            background:linear-gradient(135deg,#ec4899,#d946ef);
                            color:white;
                            font-weight:600;
                        ">

                        <?= strtoupper(substr($_SESSION['nama_lengkap'], 0, 1)) ?>

                    </div>

                    <div class="ms-3">

                        <h6 class="mb-0 fw-semibold text-dark" style="font-size:14px;">

                            <?= $_SESSION['nama_lengkap'] ?>

                        </h6>

                        <small class="text-muted">

                            <?= $_SESSION['role'] ?>

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

</aside>

<main class="main-content">

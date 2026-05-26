<aside class="sidebar" id="sidebarLayout">

    <ul class="nav-menu py-3">

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

                <i class="fa-solid fa-truck"></i>

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

                <i class="fa-solid fa-file-lines"></i>

                <span class="menu-text">
                    Laporan
                </span>

            </a>

        </li>

        <?php if ($_SESSION['role'] == 'Admin') : ?>

        <!-- USER -->
        <li class="nav-item-custom">

            <a href="index.php?page=user" class="nav-link-custom <?php echo isset($_GET['page']) && $_GET['page'] == 'user' ? 'active' : ''; ?>">

                <i class="fa-solid fa-user"></i>

                <span class="menu-text">
                    User
                </span>

            </a>

        </li>

        <?php endif; ?>

    </ul>

</aside>

<main class="main-content">

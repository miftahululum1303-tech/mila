<aside class="sidebar" id="sidebarLayout">
    <ul class="nav-menu py-3">

        <li class="nav-item-custom">
            <a href="index.php?page=profil" class="nav-link-custom <?php echo (!isset($_GET['page']) || $_GET['page'] == 'profil') ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-pie"></i>
                <span class="menu-text">Dashboard Analitik</span>
            </a>
        </li>

        <li class="nav-item-custom">
            <a href="#masterSubmenu" class="nav-link-custom <?php echo (isset($_GET['page']) && in_array($_GET['page'], ['barang', 'supplier', 'safety-stock'])) ? 'active' : ''; ?>" data-bs-toggle="collapse" role="button" aria-expanded="<?php echo (isset($_GET['page']) && in_array($_GET['page'], ['barang', 'supplier', 'safety-stock'])) ? 'true' : 'false'; ?>" aria-controls="masterSubmenu">
                <i class="fa-solid fa-folder-tree"></i>
                <span class="menu-text">Master Data</span>
                <i class="fa-solid fa-chevron-right arrow-icon small"></i>
            </a>
            <ul class="submenu collapse <?php echo (isset($_GET['page']) && in_array($_GET['page'], ['barang', 'supplier', 'safety-stock'])) ? 'show' : ''; ?>" id="masterSubmenu">
                <li>
                    <a href="index.php?page=barang" class="nav-link-custom <?php echo (isset($_GET['page']) && $_GET['page'] == 'barang') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-box"></i>
                        <span class="menu-text">Barang & Kategori</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=supplier" class="nav-link-custom <?php echo (isset($_GET['page']) && $_GET['page'] == 'supplier') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-truck-field"></i>
                        <span class="menu-text">Data Supplier</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=safety-stock" class="nav-link-custom <?php echo (isset($_GET['page']) && $_GET['page'] == 'safety-stock') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span class="menu-text">Batas Minimal Stok</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item-custom">
            <a href="#transaksiSubmenu" class="nav-link-custom <?php echo (isset($_GET['page']) && in_array($_GET['page'], ['barang-masuk', 'barang-keluar', 'retur-log'])) ? 'active' : ''; ?>" data-bs-toggle="collapse" role="button" aria-expanded="<?php echo (isset($_GET['page']) && in_array($_GET['page'], ['barang-masuk', 'barang-keluar', 'retur-log'])) ? 'true' : 'false'; ?>" aria-controls="transaksiSubmenu">
                <i class="fa-solid fa-right-left"></i>
                <span class="menu-text">Manajemen Transaksi</span>
                <i class="fa-solid fa-chevron-right arrow-icon small"></i>
            </a>
            <ul class="submenu collapse <?php echo (isset($_GET['page']) && in_array($_GET['page'], ['barang-masuk', 'barang-keluar', 'retur-log'])) ? 'show' : ''; ?>" id="transaksiSubmenu">
                <li>
                    <a href="index.php?page=barang-masuk" class="nav-link-custom <?php echo (isset($_GET['page']) && $_GET['page'] == 'barang-masuk') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-arrow-down-long text-success"></i>
                        <span class="menu-text">Barang Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=barang-keluar" class="nav-link-custom <?php echo (isset($_GET['page']) && $_GET['page'] == 'barang-keluar') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-arrow-up-long text-danger"></i>
                        <span class="menu-text">Barang Keluar</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=retur-log" class="nav-link-custom <?php echo (isset($_GET['page']) && $_GET['page'] == 'retur-log') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span class="menu-text">Retur & Log</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item-custom">
            <a href="#laporanSubmenu" class="nav-link-custom <?php echo (isset($_GET['page']) && in_array($_GET['page'], ['stok-opname', 'mutasi-stok', 'peramalan'])) ? 'active' : ''; ?>" data-bs-toggle="collapse" role="button" aria-expanded="<?php echo (isset($_GET['page']) && in_array($_GET['page'], ['stok-opname', 'mutasi-stok', 'peramalan'])) ? 'true' : 'false'; ?>" aria-controls="laporanSubmenu">
                <i class="fa-solid fa-file-chart-column"></i>
                <span class="menu-text">Laporan & Evaluasi</span>
                <i class="fa-solid fa-chevron-right arrow-icon small"></i>
            </a>
            <ul class="submenu collapse <?php echo (isset($_GET['page']) && in_array($_GET['page'], ['stok-opname', 'mutasi-stok', 'peramalan'])) ? 'show' : ''; ?>" id="laporanSubmenu">
                <li>
                    <a href="index.php?page=stok-opname" class="nav-link-custom <?php echo (isset($_GET['page']) && $_GET['page'] == 'stok-opname') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-clipboard-check"></i>
                        <span class="menu-text">Stok Opname</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=mutasi-stok" class="nav-link-custom <?php echo (isset($_GET['page']) && $_GET['page'] == 'mutasi-stok') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span class="menu-text">Keluar Masuk Berkala</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=peramalan" class="nav-link-custom <?php echo (isset($_GET['page']) && $_GET['page'] == 'peramalan') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span class="menu-text">Peramalan Stok</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item-custom">
            <a href="index.php?page=user" class="nav-link-custom <?php echo (isset($_GET['page']) && $_GET['page'] == 'user') ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-gear"></i>
                <span class="menu-text">Manajemen User</span>
            </a>
        </li>

    </ul>
</aside>

<main class="main-content">
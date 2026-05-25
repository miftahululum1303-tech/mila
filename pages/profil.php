<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0" style="color: var(--dark-color);">Dashboard & Ringkasan Sistem</h4>
        <button class="btn btn-sm btn-primary px-3 shadow-sm" style="background-color: var(--primary-color); border: none;">
            <i class="fa-solid fa-sync me-2"></i> Refresh Data
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Barang</h6>
                    <h3 class="fw-bold m-0 text-dark">1,248</h3>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                    <i class="fa-solid fa-box fa-xl"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Stok Keluar</h6>
                    <h3 class="fw-bold m-0 text-success">352</h3>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                    <i class="fa-solid fa-arrow-trend-up fa-xl"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Peringatan Stok</h6>
                    <h3 class="fw-bold m-0 text-danger">12</h3>
                </div>
                <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger">
                    <i class="fa-solid fa-triangle-exclamation fa-xl"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-3 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Pengguna Aktif</h6>
                    <h3 class="fw-bold m-0 text-warning">5</h3>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                    <i class="fa-solid fa-users fa-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-8">
            <div class="card p-4 h-100">
                <h6 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-chart-line me-2 text-primary"></i>Tren Keluar Masuk Barang (7 Hari Terakhir)</h6>
                <div style="position: relative; height:280px; width:100%;">
                    <canvas id="dashboardChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card p-4 h-100 text-center">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=150&auto=format&fit=crop" class="rounded-circle mx-auto mb-3 border border-3 border-light shadow-sm" width="100" height="100" alt="Avatar" style="object-fit: cover;">
                <h5 class="fw-bold m-0 text-dark">Administrator Utama</h5>
                <p class="text-muted small mb-3">admin@mila-inventory.id</p>
                <hr class="w-100 my-2 text-muted">
                <div class="text-start mt-2">
                    <span class="badge bg-primary mb-2" style="background-color: var(--primary-color) !important;">Sistem Info</span>
                    <p class="small m-0 text-secondary"><strong>Aplikasi:</strong> Inventory System v1.0</p>
                    <p class="small m-0 text-secondary"><strong>Database:</strong> MySQLi (mila)</p>
                    <p class="small m-0 text-secondary"><strong>Arsitektur:</strong> PHP Native Procedural Modular</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card p-4">
                <h6 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-list-check me-2 text-primary"></i>Log Transaksi Barang Terbaru (DataTables Responsive Active)</h6>
                <table id="logTable" class="table table-striped table-hover nowrap dt-responsive" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Tipe</th>
                            <th>Waktu Log</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#TRX-00192</td>
                            <td>BRG-MK01</td>
                            <td>Kertas HVS A4 80gr</td>
                            <td>ATK</td>
                            <td>10 Rim</td>
                            <td><span class="badge bg-success">Masuk</span></td>
                            <td>2026-05-25 10:15</td>
                        </tr>
                        <tr>
                            <td>#TRX-00193</td>
                            <td>BRG-EL04</td>
                            <td>Mouse Wireless Logitech</td>
                            <td>Elektronik</td>
                            <td>2 Pcs</td>
                            <td><span class="badge bg-danger">Keluar</span></td>
                            <td>2026-05-25 11:00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
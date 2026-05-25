<?php
// Pastikan file ini tidak diakses langsung tanpa melalui index.php
if (!defined('$koneksi') && !isset($koneksi)) {
    die("Akses langsung tidak diizinkan.");
}

// Menangkap sub-halaman laporan berdasarkan parameter 'page'
$sub_page = isset($_GET['page']) ? $_GET['page'] : 'stok-opname';
?>

<div class="container-fluid p-0">

    <!-- HEADER MODUL UTAMA -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold m-0" style="color: var(--dark-color);">
                <?php
                if ($sub_page == 'stok-opname') echo '<i class="fa-solid fa-clipboard-check text-primary me-2"></i>Laporan Stok Opname';
                elseif ($sub_page == 'mutasi-stok') echo '<i class="fa-solid fa-calendar-days text-success me-2"></i>Keluar Masuk Berkala (Mutasi)';
                else echo '<i class="fa-solid fa-wand-magic-sparkles text-purple me-2" style="color: #6366f1;"></i>Analisis Peramalan Stok (Forecasting)';
                ?>
            </h4>
            <small class="text-muted">Modul analisis strategi, audit fisik gudang, dan proyeksi restock inventory.</small>
        </div>

        <!-- Tombol Cetak Dokumen Eksekutif -->
        <button class="btn btn-sm btn-outline-secondary bg-white shadow-sm px-3" onclick="window.print();">
            <i class="fa-solid fa-print me-2"></i>Cetak Laporan
        </button>
    </div>

    <!-- ==========================================
         VIEW 1: LAPORAN STOK OPNAME
         ========================================== -->
    <?php if ($sub_page == 'stok-opname'): ?>
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card p-3 border-start border-4 border-primary">
                    <span class="text-muted small fw-bold text-uppercase">Total Item Diaudit</span>
                    <h4 class="fw-bold m-0 mt-1">3 Produk</h4>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card p-3 border-start border-4 border-success">
                    <span class="text-muted small fw-bold text-uppercase">Status Sesuai</span>
                    <h4 class="fw-bold m-0 mt-1">2 Produk</h4>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card p-3 border-start border-4 border-danger">
                    <span class="text-muted small fw-bold text-uppercase">Status Selisih/Minus</span>
                    <h4 class="fw-bold m-0 mt-1">1 Produk</h4>
                </div>
            </div>
        </div>

        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark m-0">Buku Hasil Audit Fisik Gudang</h5>
                <button class="btn btn-primary btn-sm px-3 shadow-sm" style="background-color: var(--primary-color); border:none;">
                    <i class="fa-solid fa-plus me-2"></i>Mulai Opname Baru
                </button>
            </div>

            <table id="tableOpname" class="table table-striped table-hover nowrap dt-responsive w-100">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal Audit</th>
                        <th>Kode / Nama Barang</th>
                        <th>Stok Sistem</th>
                        <th>Stok Fisik</th>
                        <th>Selisih</th>
                        <th>Status</th>
                        <th>Keterangan Penyesuaian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q_opname = "SELECT so.*, b.nama_barang, b.kode_barang FROM stok_opname so JOIN barang b ON so.id_barang = b.id_barang ORDER BY so.id_opname DESC";
                    $r_opname = mysqli_query($koneksi, $q_opname);
                    while ($op = mysqli_fetch_assoc($r_opname)):
                    ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($op['tanggal_opname'])); ?></td>
                            <td><strong><?php echo htmlspecialchars($op['kode_barang']); ?></strong> - <?php echo htmlspecialchars($op['nama_barang']); ?></td>
                            <td><?php echo $op['stok_sistem']; ?></td>
                            <td><?php echo $op['stok_fisik']; ?></td>
                            <td class="fw-bold <?php echo $op['selisih'] < 0 ? 'text-danger' : ($op['selisih'] > 0 ? 'text-success' : 'text-dark'); ?>">
                                <?php echo $op['selisih'] > 0 ? '+' . $op['selisih'] : $op['selisih']; ?>
                            </td>
                            <td>
                                <?php
                                if ($op['status_kondisi'] == 'Sesuai') echo '<span class="badge bg-success">Sesuai</span>';
                                elseif ($op['status_kondisi'] == 'Kurang') echo '<span class="badge bg-danger">Kurang</span>';
                                else echo '<span class="badge bg-warning text-dark">Lebih</span>';
                                ?>
                            </td>
                            <td><small class="text-muted"><?php echo htmlspecialchars($op['keterangan'] ?? '-'); ?></small></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- ==========================================
         VIEW 2: KELUAR MASUK BERKALA (MUTASI)
         ========================================== -->
    <?php elseif ($sub_page == 'mutasi-stok'): ?>
        <!-- Form Filter Periode Dokumen -->
        <div class="card p-3 mb-4">
            <form class="row g-3 align-items-end" method="GET" action="index.php">
                <input type="hidden" name="page" value="mutasi-stok">
                <div class="col-12 col-sm-4">
                    <label class="form-label small fw-bold">Tanggal Mulai Periode</label>
                    <input type="date" class="form-control form-control-sm" name="tgl_awal" value="<?php echo date('Y-m-01'); ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label small fw-bold">Tanggal Akhir Periode</label>
                    <input type="date" class="form-control form-control-sm" name="tgl_akhir" value="<?php echo date('Y-m-t'); ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <button type="submit" class="btn btn-sm btn-primary w-100" style="background-color: var(--primary-color); border:none;">
                        <i class="fa-solid fa-filter me-2"></i>Saring Data Mutasi
                    </button>
                </div>
            </form>
        </div>

        <div class="card p-4">
            <h5 class="fw-bold text-dark mb-3">Arus Rekapitulasi Mutasi Barang</h5>
            <table id="tableMutasi" class="table table-striped table-hover nowrap dt-responsive w-100">
                <thead class="table-dark">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Item</th>
                        <th>Stok Awal</th>
                        <th class="text-success">Total Masuk (+)</th>
                        <th class="text-danger">Total Keluar (-)</th>
                        <th>Stok Akhir</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query agregasi murni untuk mahasiswa belajar rekapitulasi data (pivot sederhana)
                    $q_mutasi = "SELECT b.id_barang, b.kode_barang, b.nama_barang, b.stok_sekarang, b.satuan,
                                 (SELECT IFNULL(SUM(jumlah), 0) FROM barang_masuk WHERE id_barang = b.id_barang) as total_masuk,
                                 (SELECT IFNULL(SUM(jumlah), 0) FROM barang_keluar WHERE id_barang = b.id_barang) as total_keluar
                                 FROM barang b ORDER BY b.kode_barang ASC";
                    $r_mutasi = mysqli_query($koneksi, $q_mutasi);
                    while ($mu = mysqli_fetch_assoc($r_mutasi)):
                        // Rumus hitung mundur matematis untuk edukasi mahasiswa
                        $stok_awal = $mu['stok_sekarang'] - $mu['total_masuk'] + $mu['total_keluar'];
                    ?>
                        <tr>
                            <td class="fw-bold text-primary"><?php echo $mu['kode_barang']; ?></td>
                            <td><?php echo htmlspecialchars($mu['nama_barang']); ?></td>
                            <td><?php echo $stok_awal; ?></td>
                            <td class="text-success fw-bold">+ <?php echo $mu['total_masuk']; ?></td>
                            <td class="text-danger fw-bold">- <?php echo $mu['total_keluar']; ?></td>
                            <td class="fw-bold text-dark"><?php echo $mu['stok_sekarang']; ?></td>
                            <td><span class="badge bg-light text-dark border"><?php echo $mu['satuan']; ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- ==========================================
         VIEW 3: ANALISIS PERAMALAN STOK (FORECASTING SYSTEM)
         ========================================== -->
    <?php else: ?>
        <div class="card p-4 mb-4">
            <h5 class="fw-bold text-dark mb-2"><i class="fa-solid fa-brain text-indigo me-2"></i>Engine Kecerdasan Sistem (Simulasi Moving Average)</h5>
            <p class="text-muted small">Modul ini melakukan komputasi terhadap histori transaksi barang keluar pada periode sebelumnya untuk memproyeksikan kebutuhan pengadaan stok di bulan mendatang.</p>

            <div class="row g-3 mt-1">
                <div class="col-12 col-md-6">
                    <div class="p-3 bg-light rounded-3 border h-100">
                        <h6 class="fw-bold text-dark small mb-2">Metode Komputasi Aktif</h6>
                        <span class="badge bg-dark px-3 py-2">Single Moving Average (SMA-3)</span>
                        <small class="text-muted d-block mt-2">Rumus Matematika Formulasi:</small>
                        <code class="d-block mt-1 bg-white p-2 border rounded text-dark text-center fw-bold">
                            F_(t+1) = (A_t + A_(t-1) + A_(t-2)) / 3
                        </code>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="p-3 bg-light rounded-3 border h-100">
                        <h6 class="fw-bold text-dark small mb-2">Proyeksi Target Pengadaan</h6>
                        <div class="d-flex align-items-center mt-2">
                            <h2 class="fw-bold m-0 text-primary me-3"><?php echo date('F Y', strtotime('+1 month')); ?></h2>
                            <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Data Histori Siap</span>
                        </div>
                        <small class="text-muted d-block mt-2">Gunakan prediksi ini sebagai dasar penentuan batas kuantitas PO (Purchase Order) ke supplier.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <!-- Komponen Tabel Hasil Proyeksi -->
            <div class="col-12 col-xl-7">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold text-dark mb-3">Tabel Rekomendasi Restock Hasil Peramalan</h6>
                    <table id="tablePeramalan" class="table table-striped table-hover nowrap dt-responsive w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Stok Riil</th>
                                <th>Prediksi Kebutuhan</th>
                                <th>Rekomendasi Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Kertas HVS A4 80gr</td>
                                <td>150 Rim</td>
                                <td class="fw-bold">45 Rim</td>
                                <td><span class="badge bg-secondary">Stok Sangat Aman</span></td>
                            </tr>
                            <tr>
                                <td>Mouse Wireless Logitech</td>
                                <td>8 Pcs</td>
                                <td class="fw-bold text-danger">18 Pcs</td>
                                <td><span class="badge bg-danger"><i class="fa-solid fa-cart-plus me-1"></i>Wajib Order +10 Pcs</span></td>
                            </tr>
                            <tr>
                                <td>Minyak Goreng Filma 2L</td>
                                <td>40 Pouch</td>
                                <td class="fw-bold">35 Pouch</td>
                                <td><span class="badge bg-warning text-dark">Pantau Berkala</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Komponen Visualisasi Grafik Chart.js Prediksi -->
            <div class="col-12 col-xl-5">
                <div class="card p-4 h-100">
                    <h6 class="fw-bold text-dark mb-3">Grafik Komparasi Stok Riil vs Hasil Prediksi</h6>
                    <div style="position: relative; height:240px; width:100%;">
                        <canvas id="chartForecasting"></canvas>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<!-- ==========================================
     INJEKSI INTERACTIVE JAVASCRIPT ENGINE 
     ========================================== -->
<script>
    window.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function() {
            // Inisialisasi DataTables Opname
            if ($('#tableOpname').length) {
                $('#tableOpname').DataTable({
                    responsive: true,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }
                });
            }

            // Inisialisasi DataTables Mutasi
            if ($('#tableMutasi').length) {
                $('#tableMutasi').DataTable({
                    responsive: true,
                    pageLength: 10,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }
                });
            }

            // Inisialisasi DataTables Peramalan
            if ($('#tablePeramalan').length) {
                $('#tablePeramalan').DataTable({
                    responsive: true,
                    paging: false,
                    searching: false,
                    info: false,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }
                });
            }

            // Inisialisasi Chart.js Komparasi Peramalan
            if ($('#chartForecasting').length) {
                const ctxForecast = document.getElementById('chartForecasting').getContext('2d');
                new Chart(ctxForecast, {
                    type: 'bar',
                    data: {
                        labels: ['HVS A4', 'Mouse Wire.', 'Minyak 2L'],
                        datasets: [{
                            label: 'Stok Riil Gudang',
                            data: [150, 8, 40],
                            backgroundColor: '#3e7ccb',
                            borderRadius: 4
                        }, {
                            label: 'Hasil Prediksi Kebutuhan',
                            data: [45, 18, 35],
                            backgroundColor: '#6366f1',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        });
    });
</script>
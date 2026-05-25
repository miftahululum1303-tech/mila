<?php
// Pastikan file ini tidak diakses langsung tanpa melalui index.php
if (!defined('$koneksi') && !isset($koneksi)) {
    die("Akses langsung tidak diizinkan.");
}

// Menangkap sub-halaman transaksi berdasarkan parameter 'page'
$sub_page = isset($_GET['page']) ? $_GET['page'] : 'barang-masuk';

// ==========================================
// SIMULASI NOTIFIKASI LOGIKA CRUD
// ==========================================
$notif = "";
if (isset($_POST['aksi'])) {
    $notif = '<div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> Transaksi berhasil dicatat dan stok barang telah diperbarui secara otomatis!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
}

// Ambil data referensi barang untuk keperluan Dropdown Form Tambah Transaksi
$q_ref_barang = "SELECT id_barang, kode_barang, nama_barang, stok_sekarang FROM barang ORDER BY nama_barang ASC";
$r_ref_barang = mysqli_query($koneksi, $q_ref_barang);
$barang_list = [];
while ($row = mysqli_fetch_assoc($r_ref_barang)) {
    $barang_list[] = $row;
}

// Ambil data referensi supplier untuk dropdown barang masuk
$q_ref_supplier = "SELECT id_supplier, nama_supplier FROM supplier ORDER BY nama_supplier ASC";
$r_ref_supplier = mysqli_query($koneksi, $q_ref_supplier);
$supplier_list = [];
while ($row = mysqli_fetch_assoc($r_ref_supplier)) {
    $supplier_list[] = $row;
}
?>

<div class="container-fluid p-0">

    <div class="mb-4">
        <h4 class="fw-bold m-0" style="color: var(--dark-color);">
            <?php
            if ($sub_page == 'barang-masuk') echo '<i class="fa-solid fa-arrow-down-long text-success me-2"></i>Riwayat Barang Masuk (Restock)';
            elseif ($sub_page == 'barang-keluar') echo '<i class="fa-solid fa-arrow-up-long text-danger me-2"></i>Riwayat Barang Keluar (Penjualan/Dilepas)';
            else echo '<i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Retur Barang & Log Aktivitas';
            ?>
        </h4>
        <small class="text-muted">Pencatatan mutasi sirkulasi stok logistik pergudangan.</small>
    </div>

    <?php echo $notif; ?>

    <div class="card p-4">

        <?php if ($sub_page == 'barang-masuk'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark m-0">Dokumentasi Faktur Masuk</h5>
                <button class="btn btn-success btn-sm shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#modalMasuk">
                    <i class="fa-solid fa-plus me-2"></i>Catat Barang Masuk
                </button>
            </div>

            <table id="tableMasuk" class="table table-striped table-hover nowrap dt-responsive w-100">
                <thead class="table-light">
                    <tr>
                        <th>No. Faktur</th>
                        <th>Barang</th>
                        <th>Supplier</th>
                        <th>Jumlah</th>
                        <th>Tanggal Masuk</th>
                        <th>Operator</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q_masuk = "SELECT bm.*, b.nama_barang, b.kode_barang, s.nama_supplier, u.nama_lengkap 
                                FROM barang_masuk bm
                                JOIN barang b ON bm.id_barang = b.id_barang
                                LEFT JOIN supplier s ON bm.id_supplier = s.id_supplier
                                LEFT JOIN users u ON bm.id_user = u.id_user
                                ORDER BY bm.id_masuk DESC";
                    $r_masuk = mysqli_query($koneksi, $q_masuk);
                    while ($m = mysqli_fetch_assoc($r_masuk)):
                    ?>
                        <tr>
                            <td class="fw-bold text-success"><?php echo htmlspecialchars($m['no_faktur']); ?></td>
                            <td>[<?php echo htmlspecialchars($m['kode_barang']); ?>] <?php echo htmlspecialchars($m['nama_barang']); ?></td>
                            <td><?php echo htmlspecialchars($m['nama_supplier'] ?? 'Umum/Tanpa Supplier'); ?></td>
                            <td class="fw-bold"><?php echo $m['jumlah']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($m['tanggal_masuk'])); ?></td>
                            <td><small><?php echo htmlspecialchars($m['nama_lengkap'] ?? 'System'); ?></small></td>
                            <td><span class="text-muted small"><?php echo htmlspecialchars($m['keterangan'] ?? '-'); ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($sub_page == 'barang-keluar'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark m-0">Dokumentasi Nota Keluar</h5>
                <button class="btn btn-danger btn-sm shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#modalKeluar">
                    <i class="fa-solid fa-minus me-2"></i>Catat Barang Keluar
                </button>
            </div>

            <table id="tableKeluar" class="table table-striped table-hover nowrap dt-responsive w-100">
                <thead class="table-light">
                    <tr>
                        <th>No. Nota</th>
                        <th>Barang</th>
                        <th>Jumlah Dilepas</th>
                        <th>Tanggal Keluar</th>
                        <th>Operator</th>
                        <th>Keterangan / Tujuan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q_keluar = "SELECT bk.*, b.nama_barang, b.kode_barang, u.nama_lengkap 
                                 FROM barang_keluar bk
                                 JOIN barang b ON bk.id_barang = b.id_barang
                                 LEFT JOIN users u ON bk.id_user = u.id_user
                                 ORDER BY bk.id_keluar DESC";
                    $r_keluar = mysqli_query($koneksi, $q_keluar);
                    while ($k = mysqli_fetch_assoc($r_keluar)):
                    ?>
                        <tr>
                            <td class="fw-bold text-danger"><?php echo htmlspecialchars($k['no_nota']); ?></td>
                            <td>[<?php echo htmlspecialchars($k['kode_barang']); ?>] <?php echo htmlspecialchars($k['nama_barang']); ?></td>
                            <td class="fw-bold text-danger"><?php echo $k['jumlah']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($k['tanggal_keluar'])); ?></td>
                            <td><small><?php echo htmlspecialchars($k['nama_lengkap'] ?? 'System'); ?></small></td>
                            <td><span class="text-muted small"><?php echo htmlspecialchars($k['keterangan'] ?? '-'); ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php else: ?>
            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-reply-all me-2 text-warning"></i>Data Klaim Retur Produk</h5>
                <table id="tableRetur" class="table table-striped table-hover nowrap dt-responsive w-100 mb-5">
                    <thead class="table-light">
                        <tr>
                            <th>No. Retur</th>
                            <th>Arah Jenis</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Alasan Kerusakan</th>
                            <th>Tanggal Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q_retur = "SELECT rl.*, b.nama_barang, b.kode_barang FROM retur_log rl JOIN barang b ON rl.id_barang = b.id_barang ORDER BY rl.id_retur DESC";
                        $r_retur = mysqli_query($koneksi, $q_retur);
                        while ($rt = mysqli_fetch_assoc($r_retur)):
                        ?>
                            <tr>
                                <td class="fw-bold"><?php echo htmlspecialchars($rt['no_retur']); ?></td>
                                <td>
                                    <?php echo $rt['jenis_retur'] == 'Masuk'
                                        ? '<span class="badge bg-info">Masuk (Konsumen)</span>'
                                        : '<span class="badge bg-warning text-dark">Keluar (Ke Supplier)</span>';
                                    ?>
                                </td>
                                <td>[<?php echo htmlspecialchars($rt['kode_barang']); ?>] <?php echo htmlspecialchars($rt['nama_barang']); ?></td>
                                <td class="fw-bold"><?php echo $rt['jumlah']; ?></td>
                                <td><span class="text-danger small"><?php echo htmlspecialchars($rt['alasan']); ?></span></td>
                                <td><?php echo date('d/m/Y', strtotime($rt['tanggal_retur'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <hr class="my-4">

            <div>
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user-shield me-2 text-secondary"></i>Audit Trail / Log Aktivitas Pengguna (Khusus Manajemen)</h5>
                <table id="tableLog" class="table table-sm table-hover nowrap dt-responsive w-100">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 20%">Waktu Operasional</th>
                            <th style="width: 20%">Pengguna</th>
                            <th>Aktivitas Sistem</th>
                            <th style="width: 15%">Alamat IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q_log = "SELECT la.*, u.username FROM log_aktivitas la LEFT JOIN users u ON la.id_user = u.id_user ORDER BY la.id_log DESC LIMIT 50";
                        $r_log = mysqli_query($koneksi, $q_log);
                        while ($l = mysqli_fetch_assoc($r_log)):
                        ?>
                            <tr>
                                <td><small class="text-muted"><?php echo $l['waktu_log']; ?></small></td>
                                <td class="fw-semibold text-primary">@<?php echo htmlspecialchars($l['username'] ?? 'system_cron'); ?></td>
                                <td><?php echo htmlspecialchars($l['aktivitas']); ?></td>
                                <td><code class="small"><?php echo $l['ip_address'] ?? '127.0.0.1'; ?></code></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</div>

<div class="modal fade" id="modalMasuk" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="" method="POST" class="modal-content">
            <input type="hidden" name="aksi" value="tambah_masuk">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-circle-arrow-down text-success me-2"></i>Penerimaan Faktur Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nomor Faktur / Surat Jalan</label>
                    <input type="text" class="form-control form-control-sm" name="no_faktur" placeholder="Contoh: FAK-<?php echo date('YmdHis'); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Pilih Barang</label>
                    <select class="form-select form-select-sm" name="id_barang" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($barang_list as $b): ?>
                            <option value="<?php echo $b['id_barang']; ?>"><?php echo htmlspecialchars($b['nama_barang']); ?> (Stok: <?php echo $b['stok_sekarang']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Asal Supplier</label>
                    <select class="form-select form-select-sm" name="id_supplier">
                        <option value="">-- Pemasok Umum (Non-Supplier) --</option>
                        <?php foreach ($supplier_list as $s): ?>
                            <option value="<?php echo $s['id_supplier']; ?>"><?php echo htmlspecialchars($s['nama_supplier']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Jumlah Masuk</label>
                        <input type="number" class="form-control form-control-sm" name="jumlah" min="1" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Tanggal Terima</label>
                        <input type="datetime-local" class="form-control form-control-sm" name="tanggal_masuk" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Catatan Lapangan</label>
                    <textarea class="form-control form-control-sm" name="keterangan" rows="2" placeholder="Kondisi dus rapi, segel aman..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success btn-sm px-4">Verifikasi Masuk</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalKeluar" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="" method="POST" class="modal-content">
            <input type="hidden" name="aksi" value="tambah_keluar">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-circle-arrow-up text-danger me-2"></i>Penerbitan Nota Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nomor Nota Pengeluaran</label>
                    <input type="text" class="form-control form-control-sm" name="no_nota" placeholder="Contoh: NOT-<?php echo date('YmdHis'); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Pilih Barang Keluar</label>
                    <select class="form-select form-select-sm" name="id_barang" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($barang_list as $b): ?>
                            <option value="<?php echo $b['id_barang']; ?>"><?php echo htmlspecialchars($b['nama_barang']); ?> (Tersedia: <?php echo $b['stok_sekarang']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Jumlah Keluar</label>
                        <input type="number" class="form-control form-control-sm" name="jumlah" min="1" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Tanggal Mutasi</label>
                        <input type="datetime-local" class="form-control form-control-sm" name="tanggal_keluar" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Keterangan Keperluan / Alokasi</label>
                    <textarea class="form-control form-control-sm" name="keterangan" rows="2" placeholder="Kirim ke Toko Cabang B / Keperluan Penjualan Retail..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm px-4">Proses Keluar</button>
            </div>
        </form>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function() {
            // Inisialisasi Tabel Barang Masuk
            if ($('#tableMasuk').length) {
                $('#tableMasuk').DataTable({
                    responsive: true,
                    order: [
                        [4, "desc"]
                    ], // Urutkan berdasarkan tanggal terbaru
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }
                });
            }

            // Inisialisasi Tabel Barang Keluar
            if ($('#tableKeluar').length) {
                $('#tableKeluar').DataTable({
                    responsive: true,
                    order: [
                        [3, "desc"]
                    ],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }
                });
            }

            // Inisialisasi Tabel Retur
            if ($('#tableRetur').length) {
                $('#tableRetur').DataTable({
                    responsive: true,
                    pageLength: 5,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }
                });
            }

            // Inisialisasi Tabel Audit Log
            if ($('#tableLog').length) {
                $('#tableLog').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthChange: false, // Menjaga kesederhanaan visual log murni
                    searching: true,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }
                });
            }
        });
    });
</script>
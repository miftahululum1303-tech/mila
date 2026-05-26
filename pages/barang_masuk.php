<?php
// Pastikan file ini tidak diakses langsung tanpa melalui index.php
if (!defined('$koneksi') && !isset($koneksi)) {
    die('Akses langsung tidak diizinkan.');
}

// Menangkap sub-halaman transaksi berdasarkan parameter 'page'
$sub_page = isset($_GET['page']) ? $_GET['page'] : 'barang-masuk';

$notif = '';

// ==========================================
// ALERT
// ==========================================
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'masuk') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Barang masuk berhasil disimpan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    if ($_GET['success'] == 'keluar') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Barang keluar berhasil disimpan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    if ($_GET['success'] == 'hapus_masuk') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data barang masuk berhasil dihapus.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    if ($_GET['success'] == 'hapus_keluar') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data barang keluar berhasil dihapus.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }
}

// ==========================================
// TAMBAH BARANG MASUK
// ==========================================
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah_masuk') {
    $no_faktur = $_POST['no_faktur'];
    $id_barang = $_POST['id_barang'];
    $id_supplier = $_POST['id_supplier'];
    $jumlah = $_POST['jumlah'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    mysqli_query(
        $koneksi,
        "INSERT INTO barang_masuk
        (
            no_faktur,
            id_barang,
            id_supplier,
            jumlah,
            tanggal_masuk
        )
        VALUES
        (
            '$no_faktur',
            '$id_barang',
            '$id_supplier',
            '$jumlah',
            '$tanggal_masuk'
        )",
    );

    mysqli_query(
        $koneksi,
        "UPDATE barang SET
        stok_sekarang = stok_sekarang + $jumlah
        WHERE id_barang = '$id_barang'",
    );

    echo "
    <script>
        window.location='index.php?page=barang-masuk&success=masuk';
    </script>
    ";

    exit();
}

// ==========================================
// TAMBAH BARANG KELUAR
// ==========================================
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah_keluar') {
    $no_nota = $_POST['no_nota'];
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    $tanggal_keluar = $_POST['tanggal_keluar'];

    $cek_stok = mysqli_query(
        $koneksi,
        "SELECT stok_sekarang
         FROM barang
         WHERE id_barang = '$id_barang'",
    );

    $stok = mysqli_fetch_assoc($cek_stok);

    if ($stok['stok_sekarang'] < $jumlah) {
        $notif = '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Stok barang tidak mencukupi.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    } else {
        mysqli_query(
            $koneksi,
            "INSERT INTO barang_keluar
            (
                no_nota,
                id_barang,
                jumlah,
                tanggal_keluar
            )
            VALUES
            (
                '$no_nota',
                '$id_barang',
                '$jumlah',
                '$tanggal_keluar'
            )",
        );

        mysqli_query(
            $koneksi,
            "UPDATE barang SET
            stok_sekarang = stok_sekarang - $jumlah
            WHERE id_barang = '$id_barang'",
        );

        echo "
        <script>
            window.location='index.php?page=barang-keluar&success=keluar';
        </script>
        ";

        exit();
    }
}

// ==========================================
// HAPUS BARANG MASUK
// ==========================================
if (isset($_GET['hapus_masuk'])) {
    $id = $_GET['hapus_masuk'];

    $q = mysqli_query(
        $koneksi,
        "SELECT * FROM barang_masuk
         WHERE id_masuk = '$id'",
    );

    $d = mysqli_fetch_assoc($q);

    mysqli_query(
        $koneksi,
        "UPDATE barang SET
        stok_sekarang = stok_sekarang - {$d['jumlah']}
        WHERE id_barang = '{$d['id_barang']}'",
    );

    mysqli_query(
        $koneksi,
        "DELETE FROM barang_masuk
         WHERE id_masuk = '$id'",
    );

    echo "
    <script>
        window.location='index.php?page=barang-masuk&success=hapus_masuk';
    </script>
    ";

    exit();
}

// ==========================================
// HAPUS BARANG KELUAR
// ==========================================
if (isset($_GET['hapus_keluar'])) {
    $id = $_GET['hapus_keluar'];

    $q = mysqli_query(
        $koneksi,
        "SELECT * FROM barang_keluar
         WHERE id_keluar = '$id'",
    );

    $d = mysqli_fetch_assoc($q);

    mysqli_query(
        $koneksi,
        "UPDATE barang SET
        stok_sekarang = stok_sekarang + {$d['jumlah']}
        WHERE id_barang = '{$d['id_barang']}'",
    );

    mysqli_query(
        $koneksi,
        "DELETE FROM barang_keluar
         WHERE id_keluar = '$id'",
    );

    echo "
    <script>
        window.location='index.php?page=barang-keluar&success=hapus_keluar';
    </script>
    ";

    exit();
}

// Ambil data referensi barang untuk keperluan Dropdown Form Tambah Transaksi
$q_ref_barang = 'SELECT id_barang, kode_barang, nama_barang, stok_sekarang FROM barang ORDER BY nama_barang ASC';
$r_ref_barang = mysqli_query($koneksi, $q_ref_barang);
$barang_list = [];
while ($row = mysqli_fetch_assoc($r_ref_barang)) {
    $barang_list[] = $row;
}

// Ambil data referensi supplier untuk dropdown barang masuk
$q_ref_supplier = 'SELECT id_supplier, nama_supplier FROM supplier ORDER BY nama_supplier ASC';
$r_ref_supplier = mysqli_query($koneksi, $q_ref_supplier);
$supplier_list = [];
while ($row = mysqli_fetch_assoc($r_ref_supplier)) {
    $supplier_list[] = $row;
}

// ==========================================
// GENERATE NOMOR TRANSAKSI MASUK OTOMATIS
// ==========================================
$q_kode_masuk = mysqli_query(
    $koneksi,
    "SELECT MAX(id_masuk) as id_terbesar
     FROM barang_masuk",
);

$d_kode_masuk = mysqli_fetch_assoc($q_kode_masuk);

$nomor_masuk = $d_kode_masuk['id_terbesar'] + 1;

$kode_masuk_otomatis = 'TRM-' . str_pad($nomor_masuk, 3, '0', STR_PAD_LEFT);
?>

<div class="container-fluid p-0">

    <div class="mb-4">
        <h4 class="fw-bold m-0" style="color: var(--dark-color);">
            <?php
            if ($sub_page == 'barang-masuk') {
                echo '<i class="fa-solid fa-arrow-down-long text-success me-2"></i>Data Barang Masuk';
            } elseif ($sub_page == 'barang-keluar') {
                echo '<i class="fa-solid fa-arrow-up-long text-danger me-2"></i>Data Barang Keluar';
            }
            ?>
        </h4>
        <small class="text-muted">Data transaksi barang.</small>
    </div>

    <?php echo $notif; ?>

    <div class="card p-4">

        <?php if ($sub_page == 'barang-masuk'): ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark m-0">Daftar Barang Masuk</h5>
            <button class="btn btn-success btn-sm shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#modalMasuk">
                <i class="fa-solid fa-plus me-2"></i>Catat Barang Masuk
            </button>
        </div>

        <table id="tableMasuk" class="table table-striped table-hover nowrap dt-responsive w-100">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Supplier</th>
                    <th>Jumlah</th>
                    <th>Tanggal Masuk</th>
                    <th width="10%">Aksi</th>
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
                    <td> <?php echo htmlspecialchars($m['nama_barang']); ?></td>
                    <td><?php echo htmlspecialchars($m['nama_supplier'] ?? 'Umum/Tanpa Supplier'); ?></td>
                    <td class="fw-bold"><?php echo $m['jumlah']; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($m['tanggal_masuk'])); ?></td>
                    <td>
                        <a href="index.php?page=barang-masuk&hapus_masuk=<?php echo $m['id_masuk']; ?>"
                            class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data?')">

                            <i class="fa-solid fa-trash"></i>

                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php elseif ($sub_page == 'barang-keluar'): ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark m-0">Daftar Barang Keluar</h5>
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
                    <th width="10%">Aksi</th>
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
                    <td>
                        <a href="index.php?page=barang-keluar&hapus_keluar=<?php echo $k['id_keluar']; ?>"
                            class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data?')">

                            <i class="fa-solid fa-trash"></i>

                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalMasuk" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="" method="POST" class="modal-content">
            <input type="hidden" name="aksi" value="tambah_masuk">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-dark"><i
                        class="fa-solid fa-circle-arrow-down text-success me-2"></i>Tambah Barang Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nomor Transaksi Otomatis</label>
                    <input type="text" class="form-control form-control-sm" name="no_faktur"
                        value="<?php echo $kode_masuk_otomatis; ?>" readonly>
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
                    <label class="form-label small fw-bold">Supplier</label>
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
                        <input type="number" class="form-control form-control-sm" name="jumlah" min="1"
                            required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Tanggal</label>
                        <input type="datetime-local" class="form-control form-control-sm" name="tanggal_masuk"
                            value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success btn-sm px-4">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalKeluar" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="" method="POST" class="modal-content">
            <input type="hidden" name="aksi" value="tambah_keluar">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-dark"><i
                        class="fa-solid fa-circle-arrow-up text-danger me-2"></i>Nomor Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nomor Nota Pengeluaran</label>
                    <input type="text" class="form-control form-control-sm" name="no_nota"
                        placeholder="Contoh: NOT-<?php echo date('YmdHis'); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Pilih Barang Keluar</label>
                    <select class="form-select form-select-sm" name="id_barang" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($barang_list as $b): ?>
                        <option value="<?php echo $b['id_barang']; ?>"><?php echo htmlspecialchars($b['nama_barang']); ?> (Tersedia: <?php echo $b['stok_sekarang']; ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Jumlah Keluar</label>
                        <input type="number" class="form-control form-control-sm" name="jumlah" min="1"
                            required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Tanggal</label>
                        <input type="datetime-local" class="form-control form-control-sm" name="tanggal_keluar"
                            value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm px-4">Simpan</button>
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

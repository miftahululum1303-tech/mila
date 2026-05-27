<?php
$notif = '';

if (isset($_GET['success'])) {
    if ($_GET['success'] == 'tambah') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data barang keluar berhasil ditambahkan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    if ($_GET['success'] == 'hapus') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data barang keluar berhasil dihapus.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }
}

// ==========================================
// TAMBAH BARANG KELUAR
// ==========================================
if (isset($_POST['tambah_barang_keluar'])) {
    $no_nota = $_POST['no_nota'];
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    $tanggal_keluar = $_POST['tanggal_keluar'];
    $keterangan = $_POST['keterangan'];

    // Cek stok barang
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
                tanggal_keluar,
                keterangan
            )
            VALUES
            (
                '$no_nota',
                '$id_barang',
                '$jumlah',
                '$tanggal_keluar',
                '$keterangan'
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
            window.location='index.php?page=barang-keluar&success=tambah';
        </script>
        ";

        exit();
    }
}

// ==========================================
// HAPUS BARANG KELUAR
// ==========================================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $q = mysqli_query(
        $koneksi,
        "SELECT * FROM barang_keluar
         WHERE id_keluar = '$id'",
    );

    $d = mysqli_fetch_assoc($q);

    // Kembalikan stok
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
        window.location='index.php?page=barang-keluar&success=hapus';
    </script>
    ";

    exit();
}

// ==========================================
// GENERATE NOMOR TRANSAKSI
// ==========================================
$q_kode = mysqli_query(
    $koneksi,
    "SELECT MAX(id_keluar) as id_terbesar
     FROM barang_keluar",
);

$d_kode = mysqli_fetch_assoc($q_kode);

$nomor = $d_kode['id_terbesar'] + 1;

$kode_transaksi = 'TRK-' . str_pad($nomor, 3, '0', STR_PAD_LEFT);

// Ambil data barang
$query_barang = 'SELECT * FROM barang ORDER BY nama_barang ASC';
$result_barang = mysqli_query($koneksi, $query_barang);

// Ambil data barang keluar
$query_keluar = "SELECT
                    barang_keluar.*,
                    barang.nama_barang
                FROM barang_keluar
                JOIN barang ON barang_keluar.id_barang = barang.id_barang
                ORDER BY barang_keluar.id_keluar DESC";

$result_keluar = mysqli_query($koneksi, $query_keluar);
?>

<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                Barang Keluar
            </h4>

            <small class="text-muted">
                Kelola transaksi pengeluaran barang inventory.
            </small>
        </div>

        <button class="btn btn-danger px-4 py-2 shadow-sm transaksi-btn" data-bs-toggle="modal"
            data-bs-target="#modalTambahKeluar">
            <i class="fa-solid fa-plus me-1"></i>
            Tambah Barang Keluar
        </button>

    </div>

    <?= $notif ?>

    <div class="card transaksi-card border-0">
        <div class="card-body">
            <table id="tableKeluar" class="table align-middle table-hover nowrap dt-responsive w-100 transaksi-table">
                <thead class="table-light">

                    <tr>
                        <th width="5%">No</th>
                        <th>Nomor Transaksi</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th width="15%">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    while ($data = mysqli_fetch_assoc($result_keluar)) :
                    ?>

                    <tr>

                        <td><?= $no++ ?></td>

                        <td>
                            <span class="badge-soft-danger">

                                <?= $data['no_nota'] ?>

                            </span>
                        </td>

                        <td>
                            <div class="fw-semibold text-dark">

                                <?= $data['nama_barang'] ?>

                            </div>
                        </td>

                        <td>
                            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">

                                - <?= $data['jumlah'] ?>

                            </span>
                        </td>

                        <td>
                            <small class="text-muted">

                                <i class="fa-regular fa-calendar me-1"></i>

                                <?= date('d F Y', strtotime($data['tanggal_keluar'])) ?>

                            </small>
                        </td>

                        <td>
                            <small class="text-muted">

                                <?= $data['keterangan'] ?: '-' ?>

                            </small>
                        </td>

                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="index.php?page=barang-keluar&hapus=<?= $data['id_keluar'] ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>
            </table>

        </div>

    </div>

</div>

<!-- Modal Tambah Barang Keluar -->
<div class="modal fade" id="modalTambahKeluar" tabindex="-1">

    <div class="modal-dialog">

        <form action="" method="POST" class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Tambah Barang Keluar
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">
                        Nomor Transaksi
                    </label>
                    <input type="text" name="no_nota" class="form-control" value="<?= $kode_transaksi ?>" readonly>
                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Nama Barang
                    </label>

                    <select name="id_barang" class="form-select" required>

                        <option value="">
                            -- Pilih Barang --
                        </option>

                        <?php while ($barang = mysqli_fetch_assoc($result_barang)) : ?>

                        <option value="<?= $barang['id_barang'] ?>">
                            <?= $barang['nama_barang'] ?>
                        </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Jumlah
                    </label>

                    <input type="number" name="jumlah" class="form-control" required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Tanggal
                    </label>

                    <input type="datetime-local" value="<?= date('Y-m-d\TH:i') ?>" name="tanggal_keluar"
                        class="form-control" required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Keterangan
                    </label>

                    <textarea name="keterangan" class="form-control" rows="3"></textarea>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                    Batal

                </button>

                <button type="submit" name="tambah_barang_keluar" class="btn btn-danger">

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>

<script>
    window.addEventListener('DOMContentLoaded', function() {

        $(document).ready(function() {

            if ($('#tableKeluar').length) {

                $('#tableKeluar').DataTable({

                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }

                });

            }

        });

    });
</script>

<style>
    .transaksi-card {

        border-radius: 30px;

        background: rgba(255, 255, 255, .78);

        backdrop-filter: blur(16px);

        box-shadow:
            0 10px 35px rgba(236, 72, 153, .08);

    }

    .transaksi-btn {

        border: none;

        border-radius: 16px;

        font-weight: 500;

    }

    .table {

        border-collapse: separate;

        border-spacing: 0 10px;

    }

    .table thead th {

        border: none !important;

        background: transparent !important;

        color: #6b7280;

        font-size: 14px;

        font-weight: 600;

    }

    .table tbody tr {

        background: rgba(255, 255, 255, .85);

        backdrop-filter: blur(10px);

        transition: .3s;

        box-shadow:
            0 4px 15px rgba(0, 0, 0, .03);

    }

    .table tbody tr:hover {

        transform: translateY(-2px);

    }

    .table tbody td {

        border: none;

        vertical-align: middle;

        padding: 16px 14px;

    }

    .table tbody td:first-child {

        border-radius: 18px 0 0 18px;

    }

    .table tbody td:last-child {

        border-radius: 0 18px 18px 0;

    }

    .badge-soft-danger {

        background: #fee2e2;

        color: #991b1b;

        padding: 8px 14px;

        border-radius: 12px;

        font-weight: 600;

    }

    .modal-content {

        border: none;

        border-radius: 30px;

        overflow: hidden;

        background: rgba(255, 255, 255, .96);

        backdrop-filter: blur(16px);

        box-shadow:
            0 20px 45px rgba(236, 72, 153, .15);

    }

    .modal-header {

        border-bottom: 1px solid #fce7f3;

        padding: 22px 26px;

    }

    .modal-footer {

        border-top: 1px solid #fce7f3;

        padding: 18px 26px;

    }

    .form-control,
    .form-select {

        border-radius: 14px;

        border: 1px solid #f3e8ff;

        padding: 10px 14px;

    }

    .form-control:focus,
    .form-select:focus {

        border-color: #ec4899;

        box-shadow:
            0 0 0 .15rem rgba(236, 72, 153, .15);

    }

    .dataTables_wrapper .dataTables_filter input {

        border-radius: 12px;

        border: 1px solid #f3e8ff;

        padding: 6px 12px;

    }

    .dataTables_wrapper .dataTables_length select {

        border-radius: 12px;

        border: 1px solid #f3e8ff;

    }
</style>

<?php
// Pastikan file ini tidak diakses langsung tanpa melalui index.php
if (!defined('$koneksi') && !isset($koneksi)) {
    die('Akses langsung tidak diizinkan.');
}

// ==========================================
// Proses tambah data
// ==========================================
$notif = '';

if (isset($_GET['success'])) {
    if ($_GET['success'] == 'tambah_barang') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data barang berhasil ditambahkan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    if ($_GET['success'] == 'tambah_kategori') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data kategori berhasil ditambahkan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    if ($_GET['success'] == 'edit_barang') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data barang berhasil diupdate.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    if ($_GET['success'] == 'edit_kategori') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data kategori berhasil diupdate.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    if ($_GET['success'] == 'hapus_barang') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data barang berhasil dihapus.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    if ($_GET['success'] == 'hapus_kategori') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data kategori berhasil dihapus.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }
}

if (isset($_POST['aksi'])) {
    // TAMBAH BARANG
    if ($_POST['aksi'] == 'tambah_barang') {
        $kode_barang = $_POST['kode_barang'];
        $nama_barang = $_POST['nama_barang'];
        $id_kategori = $_POST['id_kategori'];
        $satuan = $_POST['satuan'];
        $harga_beli = $_POST['harga_beli'];

        $cek_barang = mysqli_query($koneksi, "SELECT * FROM barang WHERE kode_barang = '$kode_barang'");

        if (mysqli_num_rows($cek_barang) > 0) {
            $notif = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Kode barang sudah digunakan.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        } else {
            $query = "INSERT INTO barang(kode_barang, nama_barang, id_kategori, satuan, harga_beli, stok_sekarang) VALUES ('$kode_barang', '$nama_barang', '$id_kategori', '$satuan', '$harga_beli', '0')";

            mysqli_query($koneksi, $query);

            echo "<script>window.location='index.php?page=barang&success=tambah_barang';</script>";
            exit();
        }
    }

    // UPDATE BARANG
    if ($_POST['aksi'] == 'edit_barang') {
        $id_barang = $_POST['id_barang'];
        $kode_barang = $_POST['kode_barang'];
        $nama_barang = $_POST['nama_barang'];
        $id_kategori = $_POST['id_kategori'];
        $satuan = $_POST['satuan'];
        $harga_beli = $_POST['harga_beli'];

        $query = "UPDATE barang SET
        kode_barang = '$kode_barang',
        nama_barang = '$nama_barang',
        id_kategori = '$id_kategori',
        satuan = '$satuan',
        harga_beli = '$harga_beli'
        WHERE id_barang = '$id_barang'
    ";

        mysqli_query($koneksi, $query);

        echo "
    <script>
        window.location='index.php?page=barang&success=edit_barang';
    </script>
    ";

        exit();
    }

    // TAMBAH KATEGORI
    if ($_POST['aksi'] == 'tambah_kategori') {
        $nama_kategori = $_POST['nama_kategori'];
        $deskripsi = $_POST['deskripsi'];

        $cek = mysqli_query(
            $koneksi,
            "SELECT * FROM kategori
         WHERE nama_kategori = '$nama_kategori'",
        );

        if (mysqli_num_rows($cek) > 0) {
            $notif = '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Nama kategori sudah ada.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
        } else {
            $query = "INSERT INTO kategori
        (
            nama_kategori,
            deskripsi
        )
        VALUES
        (
            '$nama_kategori',
            '$deskripsi'
        )";

            mysqli_query($koneksi, $query);

            echo "
        <script>
            window.location='index.php?page=barang&success=tambah_kategori';
        </script>
        ";

            exit();
        }
    }

    // UPDATE KATEGORI
    if ($_POST['aksi'] == 'edit_kategori') {
        $id_kategori = $_POST['id_kategori'];
        $nama_kategori = $_POST['nama_kategori'];
        $deskripsi = $_POST['deskripsi'];

        $query = "UPDATE kategori SET
        nama_kategori = '$nama_kategori',
        deskripsi = '$deskripsi'
        WHERE id_kategori = '$id_kategori'
    ";

        mysqli_query($koneksi, $query);

        echo "
    <script>
        window.location='index.php?page=barang&success=edit_kategori';
    </script>
    ";

        exit();
    }
}

if (isset($_GET['hapus_barang'])) {
    $id = $_GET['hapus_barang'];

    mysqli_query(
        $koneksi,
        "
        DELETE FROM barang
        WHERE id_barang = '$id'
    ",
    );

    echo "
    <script>
        window.location='index.php?page=barang&success=hapus_barang';
    </script>
    ";
}

// 1. Ambil Data Kategori untuk Dropdown Form & Tabel
$q_kategori = 'SELECT * FROM kategori ORDER BY nama_kategori ASC';
$r_kategori = mysqli_query($koneksi, $q_kategori);
$kategori_list = [];
while ($row = mysqli_fetch_assoc($r_kategori)) {
    $kategori_list[] = $row;
}

// 2. Ambil Data Barang dengan JOIN ke tabel Kategori
$q_barang = "SELECT barang.*, kategori.nama_kategori
             FROM barang
             LEFT JOIN kategori ON barang.id_kategori = kategori.id_kategori
             ORDER BY barang.id_barang DESC";
$r_barang = mysqli_query($koneksi, $q_barang);

// ==========================================
// GENERATE KODE BARANG OTOMATIS
// ==========================================
$q_kode = mysqli_query(
    $koneksi,
    "SELECT MAX(id_barang) as id_terbesar
     FROM barang",
);

$d_kode = mysqli_fetch_assoc($q_kode);

$nomor = $d_kode['id_terbesar'] + 1;

$kode_barang_otomatis = 'BRG-' . str_pad($nomor, 3, '0', STR_PAD_LEFT);

if (isset($_GET['hapus_kategori'])) {
    $id = $_GET['hapus_kategori'];

    mysqli_query(
        $koneksi,
        "
        DELETE FROM kategori
        WHERE id_kategori = '$id'
    ",
    );

    echo "
    <script>
        window.location='index.php?page=barang&success=hapus_kategori';
    </script>
    ";
}
?>

<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    Inventory Barang
                </h4>

                <small class="text-muted">
                    Kelola data barang dan kategori inventory.
                </small>
            </div>
        </div>
    </div>

    <?php echo $notif; ?>

    <div class="card mb-4">
        <div class="card-header bg-white border-0 p-0">
            <ul class="nav custom-tabs mb-4" id="masterTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-3 fw-semibold text-secondary" id="barang-tab" data-bs-toggle="tab"
                        data-bs-target="#barang-pane" type="button" role="tab" aria-controls="barang-pane"
                        aria-selected="true">
                        <i class="fa-solid fa-box me-2 text-primary"></i>Daftar Barang & Stok
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 fw-semibold text-secondary" id="kategori-tab" data-bs-toggle="tab"
                        data-bs-target="#kategori-pane" type="button" role="tab" aria-controls="kategori-pane"
                        aria-selected="false">
                        <i class="fa-solid fa-tags me-2 text-warning"></i>Kategori
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4 bg-white tab-content" id="masterTabContent">

            <div class="tab-pane fade show active" id="barang-pane" role="tabpanel" aria-labelledby="barang-tab"
                tabindex="0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark m-0">Daftar Barang</h5>
                    <button class="btn btn-primary btn-sm shadow-sm px-3" data-bs-toggle="modal"
                        data-bs-target="#modalTambahBarang"
                        style="background-color: var(--primary-color); border:none;">
                        <i class="fa-solid fa-plus me-2"></i>Tambah Barang
                    </button>
                </div>

                <table id="tableBarang" class="table table-striped table-hover nowrap dt-responsive w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($r_barang) == 0) : ?>

                        <tr>

                            <td colspan="6" class="text-center py-5">

                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486740.png" width="120"
                                    class="mb-3 opacity-75">

                                <h6 class="fw-bold text-muted">
                                    Belum ada data barang
                                </h6>

                            </td>

                        </tr>

                        <?php endif; ?>
                        <?php $data_barang = [];

                        while ($b = mysqli_fetch_assoc($r_barang)) {

                            $data_barang[] = $b; ?>
                        <tr>
                            <td class="fw-bold text-primary"><?php echo htmlspecialchars($b['kode_barang']); ?></td>
                            <td><?php echo htmlspecialchars($b['nama_barang']); ?></td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($b['nama_kategori'] ?? 'Tanpa Kategori'); ?></span></td>
                            <td><?php echo $b['stok_sekarang']; ?></td>
                            <td><?php echo htmlspecialchars($b['satuan']); ?></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="index.php?page=edit-barang&id=<?php echo $b['id_barang']; ?>"
                                        class="btn btn-outline-secondary">

                                        <i class="fa-solid fa-pen"></i>

                                    </a>
                                    <a href="index.php?page=barang&hapus_barang=<?php echo $b['id_barang']; ?>"
                                        class="btn btn-outline-danger"
                                        onclick="return confirm('Yakin ingin menghapus data?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="kategori-pane" role="tabpanel" aria-labelledby="kategori-tab" tabindex="0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark m-0">Data Kategori</h5>
                    <button class="btn btn-warning btn-sm text-dark shadow-sm px-3 fw-semibold" data-bs-toggle="modal"
                        data-bs-target="#modalTambahKategori">
                        <i class="fa-solid fa-folder-plus me-2"></i>Buat Kategori
                    </button>
                </div>

                <table id="tableKategori" class="table table-striped table-hover nowrap dt-responsive w-100">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 10%">No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi / Catatan Lapangan</th>
                            <th class="text-center" style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $data_kategori = [];

                        foreach ($kategori_list as $index => $kat) {
                            $data_kategori[] = $kat;
                        ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>

                            <td class="fw-bold">
                                <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($kat['deskripsi'] ?? '-'); ?>
                            </td>

                            <td class="text-center">

                                <div class="btn-group btn-group-sm">

                                    <a href="index.php?page=edit-kategori&id=<?php echo $kat['id_kategori']; ?>"
                                        class="btn btn-outline-secondary">

                                        <i class="fa-solid fa-pen"></i>

                                    </a>

                                    <a href="index.php?page=barang&hapus_kategori=<?php echo $kat['id_kategori']; ?>"
                                        class="btn btn-outline-danger"
                                        onclick="return confirm('Yakin ingin menghapus kategori?')">

                                        <i class="fa-solid fa-trash"></i>

                                    </a>

                                </div>

                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahBarang" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="" method="POST" class="modal-content">
            <input type="hidden" name="aksi" value="tambah_barang">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-box-open me-2 text-primary"></i>Tambah
                    Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0 px-4">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-bold">Kode Barang Otomatis</label>
                        <input type="text" class="form-control form-control-sm" name="kode_barang"
                            value="<?php echo $kode_barang_otomatis; ?>" readonly>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-bold">Nama Barang</label>
                        <input type="text" class="form-control form-control-sm" name="nama_barang"
                            placeholder="Masukkan nama barang" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-bold">Kategori Barang</label>
                        <select class="form-select form-select-sm" name="id_kategori" required>
                            <option value="">-- Pilih Salah Satu --</option>
                            <?php foreach ($kategori_list as $kat): ?>
                            <option value="<?php echo $kat['id_kategori']; ?>"><?php echo $kat['nama_kategori']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-bold">Satuan</label>
                        <input type="text" class="form-control form-control-sm" name="satuan"
                            placeholder="Contoh: Pcs, Rim, Box" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-bold">Harga Kulakan (Beli)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" class="form-control" name="harga_beli" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 p-4">
                <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Batalkan</button>
                <button type="submit" class="btn btn-primary btn-sm px-4"
                    style="background-color: var(--primary-color);">Simpan Item</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalTambahKategori" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="" method="POST" class="modal-content">
            <input type="hidden" name="aksi" value="tambah_kategori">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-dark"><i
                        class="fa-solid fa-folder-plus me-2 text-warning"></i>Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0 px-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Kategori</label>
                    <input type="text" class="form-control form-control-sm" name="nama_kategori"
                        placeholder="Contoh: ATK, Elektronik, dll" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Deskripsi</label>
                    <textarea class="form-control form-control-sm" name="deskripsi" rows="3"
                        placeholder="Tulis catatan deskripsi mengenai kategori ini..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-top-0 p-4">
                <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning btn-sm text-dark px-4 fw-semibold">Tambahkan</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(function() {

        $('#tableBarang').DataTable({

            responsive: true,

            pageLength: 10,

            language: {

                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'

            }

        });

        $('#tableKategori').DataTable({

            responsive: true,

            pageLength: 5,

            language: {

                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'

            }

        });

    });
</script>

<style>
    .custom-tabs {

        background: #fdf2f8;

        padding: 8px;

        border-radius: 18px;

        gap: 10px;

    }

    .custom-tabs .nav-link {

        border: none !important;

        border-radius: 14px !important;

        color: #6b7280 !important;

        font-weight: 600;

        transition: .3s;

        padding: 12px 20px;

    }

    .custom-tabs .nav-link.active {

        background:
            linear-gradient(135deg,
                #ec4899,
                #d946ef) !important;

        color: white !important;

        box-shadow:
            0 10px 20px rgba(236, 72, 153, .2);

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

        background: #fff;

        box-shadow:
            0 4px 15px rgba(0, 0, 0, .03);

        transition: .3s;

    }

    .table tbody tr:hover {

        background: #fcfcfc;

    }

    .table tbody td {

        vertical-align: middle;

        border-top: none;

        border-bottom: none;

        padding: 16px 14px;

    }

    .table tbody td:first-child {

        border-radius: 16px 0 0 16px;

    }

    .table tbody td:last-child {

        border-radius: 0 16px 16px 0;

    }

    .modal-content {

        border: none;

        border-radius: 28px;

        background: #fff;

        box-shadow:
            0 15px 40px rgba(236, 72, 153, .12);

    }

    .modal-header {

        border-bottom: 1px solid #fce7f3;

        padding: 20px 24px;

    }

    .modal-footer {

        border-top: 1px solid #fce7f3;

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

    .btn-primary {

        background:
            linear-gradient(135deg,
                #ec4899,
                #d946ef);

        border: none;

        border-radius: 14px;

    }

    .btn-warning {

        border-radius: 14px;

    }

    .btn-outline-secondary,
    .btn-outline-danger {

        border-radius: 12px;

    }

    .badge {

        padding: 8px 12px;

        border-radius: 10px;

        font-weight: 500;

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

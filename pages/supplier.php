<?php

$notif = '';

// ==========================================
// TAMBAH SUPPLIER
// ==========================================
if (isset($_POST['tambah_supplier'])) {
    $nama_supplier = $_POST['nama_supplier'];
    $telepon = $_POST['telepon'];
    $alamat = $_POST['alamat'];

    $cek = mysqli_query(
        $koneksi,
        "SELECT * FROM supplier
         WHERE nama_supplier = '$nama_supplier'",
    );

    if (mysqli_num_rows($cek) > 0) {
        $notif = '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Nama supplier sudah ada.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    } else {
        mysqli_query(
            $koneksi,
            "INSERT INTO supplier
            (
                nama_supplier,
                telepon,
                alamat
            )
            VALUES
            (
                '$nama_supplier',
                '$telepon',
                '$alamat'
            )",
        );

        echo "
        <script>
            window.location='index.php?page=supplier&success=tambah';
        </script>
        ";

        exit();
    }
}

// ==========================================
// UPDATE SUPPLIER
// ==========================================
if (isset($_POST['update_supplier'])) {
    $id_supplier = $_POST['id_supplier'];
    $nama_supplier = $_POST['nama_supplier'];
    $telepon = $_POST['telepon'];
    $alamat = $_POST['alamat'];

    mysqli_query(
        $koneksi,
        "UPDATE supplier SET
            nama_supplier = '$nama_supplier',
            telepon = '$telepon',
            alamat = '$alamat'
        WHERE id_supplier = '$id_supplier'",
    );

    echo "
    <script>
        window.location='index.php?page=supplier&success=update';
    </script>
    ";

    exit();
}

// ==========================================
// HAPUS SUPPLIER
// ==========================================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query(
        $koneksi,
        "DELETE FROM supplier
         WHERE id_supplier = '$id'",
    );

    echo "
    <script>
        window.location='index.php?page=supplier&success=hapus';
    </script>
    ";

    exit();
}

// ==========================================
// ALERT
// ==========================================
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'tambah') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data supplier berhasil ditambahkan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    if ($_GET['success'] == 'update') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data supplier berhasil diupdate.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    if ($_GET['success'] == 'hapus') {
        $notif = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data supplier berhasil dihapus.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }
}

// ==========================================
// AMBIL DATA SUPPLIER
// ==========================================
$query = 'SELECT * FROM supplier ORDER BY id_supplier DESC';
$result = mysqli_query($koneksi, $query);

?>

<div class="container-fluid p-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <h4 class="fw-bold text-dark mb-1">
                Supplier Management
            </h4>

            <small class="text-muted">
                Kelola data supplier dan pemasok barang inventory.
            </small>
        </div>

        <button class="btn btn-primary px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahSupplier">
            <i class="fa-solid fa-plus me-1"></i>
            Tambah Supplier
        </button>

    </div>

    <?= $notif ?>

    <div class="card border-0 supplier-card">
        <div class="card-body">
            <table id="tableSupplier" class="table align-middle table-hover nowrap dt-responsive w-100">
                <thead class="table-light">

                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Supplier</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th width="15%">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;
                    $data_supplier = [];

                    while ($data = mysqli_fetch_assoc($result)) :

                        $data_supplier[] = $data;
                    ?>

                    <tr>
                        <td>
                            <span class="supplier-number">

                                <?= $no++ ?>

                            </span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">

                                <?= $data['nama_supplier'] ?>

                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">

                                <i class="fa-solid fa-phone me-1 text-success"></i>

                                <?= $data['telepon'] ?>

                            </span>
                        </td>
                        <td>
                            <small class="text-muted">

                                <?= $data['alamat'] ?>

                            </small>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editSupplier<?= $data['id_supplier'] ?>">

                                    <i class="fa-solid fa-pen"></i>

                                </button>

                                <a href="index.php?page=supplier&hapus=<?= $data['id_supplier'] ?>"
                                    class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus supplier?')">

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

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambahSupplier" tabindex="-1">

    <div class="modal-dialog">

        <form action="" method="POST" class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Tambah Supplier
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="mb-3">

                    <label class="form-label">
                        Nama Supplier
                    </label>

                    <input type="text" name="nama_supplier" class="form-control" required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Telepon
                    </label>

                    <input type="text" name="telepon" class="form-control" required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Alamat
                    </label>

                    <textarea name="alamat" class="form-control" rows="3" required></textarea>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                    Batal

                </button>

                <button type="submit" name="tambah_supplier" class="btn btn-primary">

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>

<!-- MODAL EDIT -->
<?php foreach ($data_supplier as $data): ?>

<div class="modal fade" id="editSupplier<?= $data['id_supplier'] ?>" tabindex="-1">

    <div class="modal-dialog">

        <form action="" method="POST" class="modal-content">

            <input type="hidden" name="id_supplier" value="<?= $data['id_supplier'] ?>">

            <div class="modal-header">

                <h5 class="modal-title">
                    Edit Supplier
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="mb-3">

                    <label class="form-label">
                        Nama Supplier
                    </label>

                    <input type="text" name="nama_supplier" class="form-control"
                        value="<?= $data['nama_supplier'] ?>" required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Telepon
                    </label>

                    <input type="text" name="telepon" class="form-control" value="<?= $data['telepon'] ?>" required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Alamat
                    </label>

                    <textarea name="alamat" class="form-control" rows="3" required><?= $data['alamat'] ?></textarea>

                </div>

            </div>

            <div class="modal-footer">

                <button type="submit" name="update_supplier" class="btn btn-primary">

                    Update

                </button>

            </div>

        </form>

    </div>

</div>

<?php endforeach; ?>

<script>
    window.addEventListener('DOMContentLoaded', function() {

        $(document).ready(function() {

            if ($.fn.DataTable.isDataTable('#tableSupplier')) {

                $('#tableSupplier').DataTable().destroy();

            }

            $('#tableSupplier').DataTable({

                pageLength: 10,

                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                }

            });

        });

    });
</script>

<style>
    .supplier-card {

        border-radius: 28px;

        background: rgba(255, 255, 255, .78);

        backdrop-filter: blur(16px);

        box-shadow:
            0 10px 35px rgba(236, 72, 153, .08);

    }

    .supplier-number {

        width: 35px;

        height: 35px;

        border-radius: 12px;

        background:
            linear-gradient(135deg,
                #ec4899,
                #d946ef);

        display: flex;

        align-items: center;

        justify-content: center;

        color: white;

        font-weight: 600;

        font-size: 13px;

    }

    .table {

        border-collapse: separate;

        border-spacing: 0 10px;

    }

    .table thead th {

        border: none !important;

        background: transparent !important;

        color: #6b7280;

        font-weight: 600;

        font-size: 14px;

    }

    .table tbody tr {

        background: rgba(255, 255, 255, .8);

        backdrop-filter: blur(12px);

        transition: .3s;

        box-shadow:
            0 4px 15px rgba(0, 0, 0, .03);

    }

    .table tbody tr:hover {

        transform: translateY(-2px);

    }

    .table tbody td {

        border: none;

        padding: 16px 14px;

        vertical-align: middle;

    }

    .table tbody td:first-child {

        border-radius: 18px 0 0 18px;

    }

    .table tbody td:last-child {

        border-radius: 0 18px 18px 0;

    }

    .modal-content {

        border: none;

        border-radius: 28px;

        overflow: hidden;

        background: rgba(255, 255, 255, .95);

        backdrop-filter: blur(14px);

        box-shadow:
            0 15px 40px rgba(236, 72, 153, .15);

    }

    .modal-header {

        border-bottom: 1px solid #fce7f3;

    }

    .modal-footer {

        border-top: 1px solid #fce7f3;

    }

    .form-control {

        border-radius: 14px;

        border: 1px solid #f3e8ff;

        padding: 10px 14px;

    }

    .form-control:focus {

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

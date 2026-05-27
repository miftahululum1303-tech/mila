<?php

$id = $_GET['id'];

// Ambil data kategori
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM kategori
    WHERE id_kategori = '$id'",
);

$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (!$data) {
    echo "
    <script>
        alert('Data kategori tidak ditemukan');
        window.location='index.php?page=barang';
    </script>
    ";

    exit();
}

// Proses update
if (isset($_POST['update'])) {
    $nama_kategori = $_POST['nama_kategori'];

    $deskripsi = $_POST['deskripsi'];

    mysqli_query(
        $koneksi,
        "UPDATE kategori SET

        nama_kategori = '$nama_kategori',

        deskripsi = '$deskripsi'

        WHERE id_kategori = '$id'",
    );

    echo "
    <script>
        window.location='index.php?page=barang&success=edit_kategori';
    </script>
    ";

    exit();
}

?>

<div class="container-fluid p-0">

    <div class="mb-4">

        <h4 class="fw-bold text-dark mb-1">
            Edit Kategori
        </h4>

        <small class="text-muted">
            Perbarui data kategori inventory.
        </small>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <form method="POST">

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Nama Kategori
                    </label>

                    <input type="text" name="nama_kategori" class="form-control" value="<?php echo $data['nama_kategori']; ?>" required>

                </div>

                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Deskripsi
                    </label>

                    <textarea name="deskripsi" class="form-control" rows="4"><?php echo $data['deskripsi']; ?></textarea>

                </div>

                <div class="d-flex gap-2">

                    <a href="index.php?page=barang" class="btn btn-light border">

                        Kembali

                    </a>

                    <button type="submit" name="update" class="btn btn-primary">

                        <i class="fa-solid fa-floppy-disk me-2"></i>
                        Update Kategori

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<style>
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

    .btn-primary {

        background:
            linear-gradient(135deg,
                #ec4899,
                #d946ef);

        border: none;

        border-radius: 14px;

    }

    .card {

        border-radius: 24px;

    }
</style>

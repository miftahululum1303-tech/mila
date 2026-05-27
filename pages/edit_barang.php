<?php

$id = $_GET['id'];

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM barang
    WHERE id_barang = '$id'",
);

$data = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $nama_barang = $_POST['nama_barang'];

    $id_kategori = $_POST['id_kategori'];

    $satuan = $_POST['satuan'];

    $harga_beli = $_POST['harga_beli'];

    mysqli_query(
        $koneksi,
        "UPDATE barang SET

        nama_barang = '$nama_barang',

        id_kategori = '$id_kategori',

        satuan = '$satuan',

        harga_beli = '$harga_beli'

        WHERE id_barang = '$id'",
    );

    echo "
    <script>
        window.location='index.php?page=barang&success=edit_barang';
    </script>
    ";
}
?>

<div class="card border-0 shadow-sm">

    <div class="card-body p-4">

        <h4 class="fw-bold mb-4">
            Edit Barang
        </h4>

        <form method="POST">

            <div class="mb-3">

                <label class="form-label">
                    Nama Barang
                </label>

                <input type="text" name="nama_barang" class="form-control" value="<?= $data['nama_barang'] ?>" required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Satuan
                </label>

                <input type="text" name="satuan" class="form-control" value="<?= $data['satuan'] ?>" required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Harga Beli
                </label>

                <input type="number" name="harga_beli" class="form-control" value="<?= $data['harga_beli'] ?>"
                    required>

            </div>

            <button type="submit" name="update" class="btn btn-primary">

                Update Data

            </button>

        </form>

    </div>

</div>

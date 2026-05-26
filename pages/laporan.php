<?php
// Total barang
$q_barang = mysqli_query($koneksi, 'SELECT COUNT(*) as total FROM barang');
$total_barang = mysqli_fetch_assoc($q_barang);

// Total supplier
$q_supplier = mysqli_query($koneksi, 'SELECT COUNT(*) as total FROM supplier');
$total_supplier = mysqli_fetch_assoc($q_supplier);

// Total barang masuk
$q_masuk = mysqli_query($koneksi, 'SELECT SUM(jumlah) as total FROM barang_masuk');
$total_masuk = mysqli_fetch_assoc($q_masuk);

// Total barang keluar
$q_keluar = mysqli_query($koneksi, 'SELECT SUM(jumlah) as total FROM barang_keluar');
$total_keluar = mysqli_fetch_assoc($q_keluar);

// Total stok
$q_stok = mysqli_query(
    $koneksi,
    "SELECT SUM(stok_sekarang) as total_stok
     FROM barang",
);

$total_stok = mysqli_fetch_assoc($q_stok);

// Total nilai barang
$q_nilai = mysqli_query(
    $koneksi,
    "SELECT SUM(stok_sekarang * harga_beli) as total_nilai
     FROM barang",
);

$total_nilai = mysqli_fetch_assoc($q_nilai);
?>

<div class="container-fluid p-0">

    <div class="mb-4">

        <h4 class="fw-bold m-0">
            Laporan
        </h4>

        <small class="text-muted">
            Data laporan persediaan barang.
        </small>

    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-2">
            <div class="card border-0 shadow-sm p-3">

                <h6 class="text-muted">
                    Total Barang
                </h6>

                <h3 class="fw-bold">
                    <?= $total_barang['total'] ?>
                </h3>

            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm p-3">

                <h6 class="text-muted">
                    Total Supplier
                </h6>

                <h3 class="fw-bold">
                    <?= $total_supplier['total'] ?>
                </h3>

            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm p-3">

                <h6 class="text-muted">
                    Barang Masuk
                </h6>

                <h3 class="fw-bold text-success">
                    <?= $total_masuk['total'] ?? 0 ?>
                </h3>

            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm p-3">

                <h6 class="text-muted">
                    Barang Keluar
                </h6>

                <h3 class="fw-bold text-danger">
                    <?= $total_keluar['total'] ?? 0 ?>
                </h3>

            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm p-3">

                <h6 class="text-muted">
                    Total Stok
                </h6>

                <h3 class="fw-bold text-primary">
                    <?= $total_stok['total_stok'] ?? 0 ?>
                </h3>

            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm p-3">

                <h6 class="text-muted">
                    Nilai Barang
                </h6>

                <h5 class="fw-bold text-dark">
                    Rp <?= number_format($total_nilai['total_nilai'] ?? 0, 0, ',', '.') ?>
                </h5>

            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h5 class="mb-3">
                Data Persediaan Barang
            </h5>

            <table id="tableLaporan" class="table table-striped table-hover nowrap dt-responsive w-100">
                <thead class="table-light">

                    <tr>
                        <th width="5%">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Harga Beli</th>
                        <th>Total Nilai</th>
                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    $query = mysqli_query($koneksi, "
                        SELECT
                            barang.*,
                            kategori.nama_kategori
                        FROM barang
                        LEFT JOIN kategori
                        ON barang.id_kategori = kategori.id_kategori
                        ORDER BY barang.nama_barang ASC
                    ");

                    while ($data = mysqli_fetch_assoc($query)) :
                    ?>

                    <tr>

                        <td><?= $no++ ?></td>

                        <td>
                            <?= $data['kode_barang'] ?>
                        </td>

                        <td>
                            <?= $data['nama_barang'] ?>
                        </td>

                        <td>
                            <?= $data['nama_kategori'] ?>
                        </td>

                        <td>
                            <?= $data['stok_sekarang'] ?>
                        </td>

                        <td>
                            <?= $data['satuan'] ?>
                        </td>
                        <td>
                            Rp <?= number_format($data['harga_beli'], 0, ',', '.') ?>
                        </td>
                        <td>
                            Rp <?= number_format($data['stok_sekarang'] * $data['harga_beli'], 0, ',', '.') ?>
                        </td>
                    </tr>

                    <?php endwhile; ?>

                </tbody>
            </table>

        </div>

    </div>

</div>

<script>
    window.addEventListener('DOMContentLoaded', function() {

        $(document).ready(function() {

            if ($('#tableLaporan').length) {

                $('#tableLaporan').DataTable({

                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }

                });

            }

        });

    });
</script>

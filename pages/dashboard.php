<?php

// ==========================================
// TOTAL DATA
// ==========================================
$q_barang = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) as total
     FROM barang",
);

$total_barang = mysqli_fetch_assoc($q_barang);

$q_supplier = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) as total
     FROM supplier",
);

$total_supplier = mysqli_fetch_assoc($q_supplier);

$q_masuk = mysqli_query(
    $koneksi,
    "SELECT SUM(jumlah) as total
     FROM barang_masuk",
);

$total_masuk = mysqli_fetch_assoc($q_masuk);

$q_keluar = mysqli_query(
    $koneksi,
    "SELECT SUM(jumlah) as total
     FROM barang_keluar",
);

$total_keluar = mysqli_fetch_assoc($q_keluar);

// ==========================================
// TRANSAKSI TERBARU
// ==========================================
$query_transaksi = mysqli_query(
    $koneksi,
    "
    SELECT
        barang.nama_barang,
        barang_masuk.jumlah,
        barang_masuk.tanggal_masuk as tanggal,
        'Barang Masuk' as jenis
    FROM barang_masuk
    JOIN barang
    ON barang_masuk.id_barang = barang.id_barang

    UNION

    SELECT
        barang.nama_barang,
        barang_keluar.jumlah,
        barang_keluar.tanggal_keluar as tanggal,
        'Barang Keluar' as jenis
    FROM barang_keluar
    JOIN barang
    ON barang_keluar.id_barang = barang.id_barang

    ORDER BY tanggal DESC
    LIMIT 5
    ",
);

?>

<div class="container-fluid p-0">

    <div class="mb-4">

        <h4 class="fw-bold text-dark">
            Dashboard
        </h4>

        <p class="text-muted mb-0">

            Selamat datang,
            <strong><?= $_SESSION['nama_lengkap'] ?></strong>

        </p>

    </div>

    <!-- ========================================== -->
    <!-- DASHBOARD ADMIN -->
    <!-- ========================================== -->
    <?php if ($_SESSION['role'] == 'Admin') : ?>

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm border-0 p-3">

                <h6 class="text-muted">
                    Total Barang
                </h6>

                <h3 class="fw-bold">
                    <?= $total_barang['total'] ?>
                </h3>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0 p-3">

                <h6 class="text-muted">
                    Barang Masuk
                </h6>

                <h3 class="fw-bold text-success">
                    <?= $total_masuk['total'] ?? 0 ?>
                </h3>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0 p-3">

                <h6 class="text-muted">
                    Barang Keluar
                </h6>

                <h3 class="fw-bold text-danger">
                    <?= $total_keluar['total'] ?? 0 ?>
                </h3>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0 p-3">

                <h6 class="text-muted">
                    Supplier
                </h6>

                <h3 class="fw-bold text-primary">
                    <?= $total_supplier['total'] ?>
                </h3>

            </div>

        </div>

    </div>

    <?php endif; ?>

    <!-- ========================================== -->
    <!-- DASHBOARD PETUGAS -->
    <!-- ========================================== -->
    <?php if ($_SESSION['role'] == 'Petugas') : ?>

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card shadow-sm border-0 p-3">

                <h6 class="text-muted">
                    Barang Masuk Hari Ini
                </h6>

                <h3 class="fw-bold text-success">

                    <?php

                    $hari_ini_masuk = mysqli_query(
                        $koneksi,
                        "SELECT SUM(jumlah) as total
                                             FROM barang_masuk
                                             WHERE tanggal_masuk = CURDATE()",
                    );

                    $masuk = mysqli_fetch_assoc($hari_ini_masuk);

                    echo $masuk['total'] ?? 0;

                    ?>

                </h3>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow-sm border-0 p-3">

                <h6 class="text-muted">
                    Barang Keluar Hari Ini
                </h6>

                <h3 class="fw-bold text-danger">

                    <?php

                    $hari_ini_keluar = mysqli_query(
                        $koneksi,
                        "SELECT SUM(jumlah) as total
                                             FROM barang_keluar
                                             WHERE tanggal_keluar = CURDATE()",
                    );

                    $keluar = mysqli_fetch_assoc($hari_ini_keluar);

                    echo $keluar['total'] ?? 0;

                    ?>

                </h3>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow-sm border-0 p-3">

                <h6 class="text-muted">
                    Total Barang
                </h6>

                <h3 class="fw-bold text-primary">
                    <?= $total_barang['total'] ?>
                </h3>

            </div>

        </div>

    </div>

    <?php endif; ?>

    <!-- ========================================== -->
    <!-- TRANSAKSI TERBARU -->
    <!-- ========================================== -->

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <h5 class="mb-3">
                Data Transaksi Terbaru
            </h5>

            <table class="table table-bordered table-striped">

                <thead class="table-light">

                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Barang</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    while ($trx = mysqli_fetch_assoc($query_transaksi)) :
                    ?>

                    <tr>

                        <td><?= $no++ ?></td>

                        <td>
                            <?= $trx['nama_barang'] ?>
                        </td>

                        <td>

                            <?php if ($trx['jenis'] == 'Barang Masuk') : ?>

                            <span class="badge bg-success">
                                Barang Masuk
                            </span>

                            <?php else : ?>

                            <span class="badge bg-danger">
                                Barang Keluar
                            </span>

                            <?php endif; ?>

                        </td>

                        <td>
                            <?= $trx['jumlah'] ?>
                        </td>

                        <td>
                            <?= date('d-m-Y', strtotime($trx['tanggal'])) ?>
                        </td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

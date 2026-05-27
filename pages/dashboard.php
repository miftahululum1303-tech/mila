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
// DATA CHART 7 HARI TERAKHIR
// ==========================================

$data_masuk = [];
$data_keluar = [];
$label_hari = [];

for ($i = 6; $i >= 0; $i--) {
    $tanggal = date('Y-m-d', strtotime("-$i days"));
    $label = date('d M', strtotime($tanggal));

    $label_hari[] = $label;

    // BARANG MASUK
    $qMasuk = mysqli_query(
        $koneksi,
        "SELECT SUM(jumlah) as total
         FROM barang_masuk
         WHERE DATE(tanggal_masuk) = '$tanggal'",
    );

    $m = mysqli_fetch_assoc($qMasuk);

    $data_masuk[] = $m['total'] ?? 0;

    // BARANG KELUAR
    $qKeluar = mysqli_query(
        $koneksi,
        "SELECT SUM(jumlah) as total
         FROM barang_keluar
         WHERE DATE(tanggal_keluar) = '$tanggal'",
    );

    $k = mysqli_fetch_assoc($qKeluar);

    $data_keluar[] = $k['total'] ?? 0;
}

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

    <div class="row g-4 mb-4">

        <!-- TOTAL BARANG -->
        <div class="col-md-3">

            <div class="dashboard-card bg-pink">

                <div>

                    <small>Total Barang</small>

                    <h3>
                        <?= $total_barang['total'] ?>
                    </h3>

                </div>

                <div class="card-icon">

                    <i class="fa-solid fa-box"></i>

                </div>

            </div>

        </div>

        <!-- BARANG MASUK -->
        <div class="col-md-3">

            <div class="dashboard-card bg-purple">

                <div>

                    <small>Barang Masuk</small>

                    <h3>
                        <?= $total_masuk['total'] ?? 0 ?>
                    </h3>

                </div>

                <div class="card-icon">

                    <i class="fa-solid fa-arrow-down"></i>

                </div>

            </div>

        </div>

        <!-- BARANG KELUAR -->
        <div class="col-md-3">

            <div class="dashboard-card bg-blue">

                <div>

                    <small>Barang Keluar</small>

                    <h3>
                        <?= $total_keluar['total'] ?? 0 ?>
                    </h3>

                </div>

                <div class="card-icon">

                    <i class="fa-solid fa-arrow-up"></i>

                </div>

            </div>

        </div>

        <!-- SUPPLIER -->
        <div class="col-md-3">

            <div class="dashboard-card bg-orange">

                <div>

                    <small>Supplier</small>

                    <h3>
                        <?= $total_supplier['total'] ?>
                    </h3>

                </div>

                <div class="card-icon">

                    <i class="fa-solid fa-truck-fast"></i>

                </div>

            </div>

        </div>

    </div>

    <?php endif; ?>

    <!-- ========================================== -->
    <!-- DASHBOARD PETUGAS -->
    <!-- ========================================== -->
    <?php if ($_SESSION['role'] == 'Petugas') : ?>

    <div class="row g-4 mb-4">

        <div class="col-md-4">

            <div class="dashboard-card bg-purple">

                <div>

                    <small>Barang Masuk Hari Ini</small>

                    <h3>

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

                <div class="card-icon">

                    <i class="fa-solid fa-arrow-down"></i>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="dashboard-card bg-blue">

                <div>

                    <small>Barang Keluar Hari Ini</small>

                    <h3>

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

                <div class="card-icon">

                    <i class="fa-solid fa-arrow-up"></i>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="dashboard-card bg-pink">

                <div>

                    <small>Total Barang</small>

                    <h3>
                        <?= $total_barang['total'] ?>
                    </h3>

                </div>

                <div class="card-icon">

                    <i class="fa-solid fa-box"></i>

                </div>

            </div>

        </div>

    </div>

    <?php endif; ?>

    <div class="row mb-4">

        <div class="col-md-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Statistik Barang
                            </h5>

                            <small class="text-muted">
                                Grafik transaksi barang
                            </small>

                        </div>

                    </div>

                    <div style="height:300px;">

                        <canvas id="dashboardChart"></canvas>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">
                        Ringkasan Sistem
                    </h5>

                    <div class="summary-item">

                        <span>Total Barang</span>

                        <strong>
                            <?= $total_barang['total'] ?>
                        </strong>

                    </div>

                    <div class="summary-item">

                        <span>Total Supplier</span>

                        <strong>
                            <?= $total_supplier['total'] ?>
                        </strong>

                    </div>

                    <div class="summary-item">

                        <span>Total Barang Masuk</span>

                        <strong>
                            <?= $total_masuk['total'] ?? 0 ?>
                        </strong>

                    </div>

                    <div class="summary-item border-0">

                        <span>Total Barang Keluar</span>

                        <strong>
                            <?= $total_keluar['total'] ?? 0 ?>
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- ========================================== -->
    <!-- TRANSAKSI TERBARU -->
    <!-- ========================================== -->

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <h5 class="mb-3">
                Data Transaksi Terbaru
            </h5>

            <table class="table align-middle table-hover">

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

<style>
    .dashboard-card {

        border-radius: 24px;

        padding: 25px;

        color: white;

        display: flex;

        align-items: center;

        justify-content: space-between;

        overflow: hidden;

        position: relative;

        box-shadow:
            0 10px 30px rgba(0, 0, 0, .08);

    }

    .dashboard-card small {

        opacity: .9;

        font-size: 14px;

    }

    .dashboard-card h3 {

        font-size: 32px;

        font-weight: 700;

        margin-top: 8px;

    }

    .card-icon {

        width: 70px;

        height: 70px;

        border-radius: 20px;

        background: rgba(255, 255, 255, .2);

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 28px;

    }

    .bg-pink {

        background:
            linear-gradient(135deg,
                #ec4899,
                #f472b6);

    }

    .bg-purple {

        background:
            linear-gradient(135deg,
                #8b5cf6,
                #c084fc);

    }

    .bg-blue {

        background:
            linear-gradient(135deg,
                #3b82f6,
                #60a5fa);

    }

    .bg-orange {

        background:
            linear-gradient(135deg,
                #f59e0b,
                #fbbf24);

    }

    .summary-item {

        display: flex;

        align-items: center;

        justify-content: space-between;

        padding: 15px 0;

        border-bottom: 1px solid #f3f4f6;

    }

    .summary-item span {

        color: #6b7280;

    }

    .summary-item strong {

        color: #111827;

    }
</style>

<script>
    window.addEventListener('DOMContentLoaded', function() {

        $(document).ready(function() {
            if ($('#dashboardChart').length) {

                const ctx = document
                    .getElementById('dashboardChart')
                    .getContext('2d');

                new Chart(ctx, {

                    type: 'line',

                    data: {

                        labels: <?= json_encode($label_hari) ?>,

                        datasets: [

                            {
                                label: 'Barang Masuk',

                                data: <?= json_encode($data_masuk) ?>,

                                borderColor: '#ec4899',

                                backgroundColor: 'rgba(236,72,153,0.12)',

                                fill: true,

                                tension: 0.4
                            },

                            {
                                label: 'Barang Keluar',

                                data: <?= json_encode($data_keluar) ?>,

                                borderColor: '#8b5cf6',

                                backgroundColor: 'rgba(139,92,246,0.10)',

                                fill: true,

                                tension: 0.4
                            }

                        ]

                    },

                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        plugins: {

                            legend: {

                                position: 'top',

                                labels: {

                                    usePointStyle: true,
                                    padding: 20

                                }

                            }

                        },

                        scales: {

                            y: {

                                beginAtZero: true,

                                grid: {

                                    color: 'rgba(0,0,0,0.04)'

                                }

                            },

                            x: {

                                grid: {

                                    display: false

                                }

                            }

                        }

                    }

                });

            }
        });

    });
</script>

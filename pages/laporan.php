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
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                Laporan Inventory
            </h4>

            <small class="text-muted">
                Ringkasan data persediaan dan transaksi barang inventory.
            </small>
        </div>
        <div>
            <button class="btn btn-primary px-4 py-2 shadow-sm rounded-4" onclick="window.print()">

                <i class="fa-solid fa-print me-2"></i>

                Cetak Laporan

            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-2">
            <div class="laporan-card">
                <div class="laporan-icon bg-pink">

                    <i class="fa-solid fa-box"></i>

                </div>

                <h6 class="text-muted mt-3">
                    Total Barang
                </h6>
                <h3 class="fw-bold">
                    <?= $total_barang['total'] ?>
                </h3>

            </div>
        </div>

        <div class="col-md-2">
            <div class="laporan-card">
                <div class="laporan-icon bg-purple">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <h6 class="text-muted">
                    Total Supplier
                </h6>

                <h3 class="fw-bold">
                    <?= $total_supplier['total'] ?>
                </h3>

            </div>
        </div>

        <div class="col-md-2">
            <div class="laporan-card">
                <div class="laporan-icon bg-success-soft">
                    <i class="fa-solid fa-arrow-down"></i>
                </div>
                <h6 class="text-muted">
                    Barang Masuk
                </h6>

                <h3 class="fw-bold text-success">
                    <?= $total_masuk['total'] ?? 0 ?>
                </h3>

            </div>
        </div>

        <div class="col-md-2">
            <div class="laporan-card">
                <div class="laporan-icon bg-danger-soft">
                    <i class="fa-solid fa-arrow-up"></i>
                </div>
                <h6 class="text-muted">
                    Barang Keluar
                </h6>

                <h3 class="fw-bold text-danger">
                    <?= $total_keluar['total'] ?? 0 ?>
                </h3>

            </div>
        </div>

        <div class="col-md-2">
            <div class="laporan-card">
                <div class="laporan-icon bg-blue">
                    <i class="fa-solid fa-cubes"></i>
                </div>
                <h6 class="text-muted">
                    Total Stok
                </h6>

                <h3 class="fw-bold text-primary">
                    <?= $total_stok['total_stok'] ?? 0 ?>
                </h3>

            </div>
        </div>

        <div class="col-md-2">
            <div class="laporan-card">
                <div class="laporan-icon bg-orange">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <h6 class="text-muted">
                    Nilai Barang
                </h6>

                <h5 class="fw-bold text-dark">
                    Rp <?= number_format($total_nilai['total_nilai'] ?? 0, 0, ',', '.') ?>
                </h5>

            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">

            <div class="card border-0 laporan-table-card">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Statistik Inventory
                            </h5>

                            <small class="text-muted">
                                Grafik transaksi barang inventory
                            </small>

                        </div>

                    </div>

                    <div style="height:320px;">

                        <canvas id="laporanChart"></canvas>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 laporan-table-card h-100">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">
                        Ringkasan
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

                        <span>Total Stok</span>

                        <strong>
                            <?= $total_stok['total_stok'] ?? 0 ?>
                        </strong>

                    </div>

                    <div class="summary-item border-0">

                        <span>Total Nilai</span>

                        <strong>

                            Rp <?= number_format($total_nilai['total_nilai'] ?? 0, 0, ',', '.') ?>

                        </strong>

                    </div>

                </div>

            </div>

        </div>
    </div>

    <div class="card border-0 laporan-table-card">
        <div class="card-body">

            <h5 class="mb-3">
                Data Persediaan Barang
            </h5>

            <table id="tableLaporan" class="table align-middle table-hover nowrap dt-responsive w-100 laporan-table">
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

            if ($('#laporanChart').length) {

                const ctx = document
                    .getElementById('laporanChart')
                    .getContext('2d');

                new Chart(ctx, {

                    type: 'bar',

                    data: {

                        labels: [
                            'Barang Masuk',
                            'Barang Keluar',
                            'Total Stok'
                        ],

                        datasets: [{

                            label: 'Jumlah',

                            data: [

                                <?= $total_masuk['total'] ?? 0 ?>,
                                <?= $total_keluar['total'] ?? 0 ?>,
                                <?= $total_stok['total_stok'] ?? 0 ?>

                            ],

                            borderRadius: 14,

                            backgroundColor: [
                                '#10b981',
                                '#ef4444',
                                '#ec4899'
                            ]

                        }]

                    },

                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        plugins: {

                            legend: {
                                display: false
                            }

                        }

                    }

                });

            }
        });
    });
</script>

<style>
    .laporan-card {

        border-radius: 28px;

        background: rgba(255, 255, 255, .8);

        backdrop-filter: blur(14px);

        padding: 24px;

        height: 100%;

        box-shadow:
            0 10px 35px rgba(236, 72, 153, .08);

        transition: .3s;

    }

    .laporan-card:hover {

        transform: translateY(-4px);

    }

    .laporan-icon {

        width: 55px;

        height: 55px;

        border-radius: 18px;

        display: flex;

        align-items: center;

        justify-content: center;

        color: white;

        font-size: 22px;

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

    .bg-success-soft {

        background:
            linear-gradient(135deg,
                #10b981,
                #34d399);

    }

    .bg-danger-soft {

        background:
            linear-gradient(135deg,
                #ef4444,
                #f87171);

    }

    .laporan-table-card {

        border-radius: 30px;

        background: rgba(255, 255, 255, .82);

        backdrop-filter: blur(14px);

        box-shadow:
            0 10px 35px rgba(236, 72, 153, .08);

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

        background: rgba(255, 255, 255, .9);

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

    .summary-item {

        display: flex;

        justify-content: space-between;

        align-items: center;

        padding: 16px 0;

        border-bottom: 1px solid #f3f4f6;

    }

    .summary-item span {

        color: #6b7280;

    }

    .summary-item strong {

        color: #111827;

    }
</style>

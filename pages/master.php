<?php
// Pastikan file ini tidak diakses langsung tanpa melalui index.php
if (!defined('$koneksi') && !isset($koneksi)) {
    die("Akses langsung tidak diizinkan.");
}

// ==========================================
// PROSES DUMMY SIMULASI LOGIK CRUD (Bahan Ajar)
// ==========================================
$notif = "";
if (isset($_POST['aksi'])) {
    // Di sini mahasiswa bisa meletakkan logika mysqli_query() untuk INSERT/UPDATE/DELETE
    // Contoh notifikasi sukses buatan:
    $notif = '<div class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> Data berhasil diperbarui! (Simulasi Logika Database Berhasil)
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
}

// 1. Ambil Data Kategori untuk Dropdown Form & Tabel
$q_kategori = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
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
?>

<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold m-0" style="color: var(--dark-color);">Master Kontrol Produk</h4>
            <small class="text-muted">Manajemen item inventaris, klasifikasi kategori, dan ambang batas kontrol.</small>
        </div>
    </div>

    <?php echo $notif; ?>

    <div class="card mb-4">
        <div class="card-header bg-white border-0 p-0">
            <ul class="nav nav-tabs nav-justified border-bottom" id="masterTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-3 fw-semibold text-secondary" id="barang-tab" data-bs-toggle="tab" data-bs-target="#barang-pane" type="button" role="tab" aria-controls="barang-pane" aria-selected="true">
                        <i class="fa-solid fa-box me-2 text-primary"></i>Daftar Barang & Stok
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 fw-semibold text-secondary" id="kategori-tab" data-bs-toggle="tab" data-bs-target="#kategori-pane" type="button" role="tab" aria-controls="kategori-pane" aria-selected="false">
                        <i class="fa-solid fa-tags me-2 text-warning"></i>Klasifikasi Kategori
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4 bg-white tab-content" id="masterTabContent">

            <div class="tab-pane fade show active" id="barang-pane" role="tabpanel" aria-labelledby="barang-tab" tabindex="0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark m-0">Gudang Penyimpanan Item</h5>
                    <button class="btn btn-primary btn-sm shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTambahBarang" style="background-color: var(--primary-color); border:none;">
                        <i class="fa-solid fa-plus me-2"></i>Tambah Barang Baru
                    </button>
                </div>

                <table id="tableBarang" class="table table-striped table-hover nowrap dt-responsive w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Item</th>
                            <th>Kategori</th>
                            <th>Stok Saat Ini</th>
                            <th>Satuan</th>
                            <th>Harga Jual</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($b = mysqli_fetch_assoc($r_barang)): ?>
                            <tr>
                                <td class="fw-bold text-primary"><?php echo htmlspecialchars($b['kode_barang']); ?></td>
                                <td><?php echo htmlspecialchars($b['nama_barang']); ?></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($b['nama_kategori'] ?? 'Tanpa Kategori'); ?></span></td>
                                <td>
                                    <?php if ($b['stok_sekarang'] <= $b['stok_minimum']): ?>
                                        <span class="badge bg-danger text-white px-2">
                                            <i class="fa-solid fa-triangle-exclamation me-1"></i><?php echo $b['stok_sekarang']; ?> (Kritis)
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success px-2"><?php echo $b['stok_sekarang']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($b['satuan']); ?></td>
                                <td>Rp <?php echo number_format($b['harga_jual'], 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-secondary" title="Edit Item"><i class="fa-solid fa-pen"></i></button>
                                        <button class="btn btn-outline-danger" title="Hapus Item"><i class="fa-solid fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="kategori-pane" role="tabpanel" aria-labelledby="kategori-tab" tabindex="0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark m-0">Grupifikasi Produk</h5>
                    <button class="btn btn-warning btn-sm text-dark shadow-sm px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
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
                        <?php foreach ($kategori_list as $index => $kat): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($kat['nama_kategori']); ?></td>
                                <td><?php echo htmlspecialchars($kat['deskripsi'] ?? '-'); ?></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-secondary"><i class="fa-solid fa-marker"></i></button>
                                        <button class="btn btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-box-open me-2 text-primary"></i>Registrasi Barang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0 px-4">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-bold">Kode Barang (Sistem QR/Barcode)</label>
                        <input type="text" class="form-control form-control-sm" name="kode_barang" placeholder="Contoh: BRG-MK02" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-bold">Nama Lengkap Barang</label>
                        <input type="text" class="form-control form-control-sm" name="nama_barang" placeholder="Masukkan nama barang" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-bold">Klasifikasi Kategori</label>
                        <select class="form-select form-select-sm" name="id_kategori" required>
                            <option value="">-- Pilih Salah Satu --</option>
                            <?php foreach ($kategori_list as $kat): ?>
                                <option value="<?php echo $kat['id_kategori']; ?>"><?php echo $kat['nama_kategori']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-bold">Satuan Ukuran</label>
                        <input type="text" class="form-control form-control-sm" name="satuan" placeholder="Contoh: Pcs, Rim, Box" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-bold">Harga Kulakan (Beli)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" class="form-control" name="harga_beli" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-bold">Harga Penjualan</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" class="form-control" name="harga_jual" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-shield-halved text-danger fa-xl me-3"></i>
                                <div class="w-100">
                                    <label class="form-label small fw-bold m-0 text-danger">Safety Stock Minimum (Batas Reorder Point)</label>
                                    <input type="number" class="form-control form-control-sm mt-1" name="stok_minimum" value="10" required>
                                    <small class="text-muted d-block mt-1">Sistem akan otomatis memicu alert merah jika kuantitas barang menyentuh atau berada di bawah batas ini.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 p-4">
                <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Batalkan</button>
                <button type="submit" class="btn btn-primary btn-sm px-4" style="background-color: var(--primary-color);">Simpan Item</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalTambahKategori" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="" method="POST" class="modal-content">
            <input type="hidden" name="aksi" value="tambah_kategori">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-folder-plus me-2 text-warning"></i>Buat Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0 px-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Kategori</label>
                    <input type="text" class="form-control form-control-sm" name="nama_kategori" placeholder="Contoh: ATK, Elektronik, dll" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Keterangan Tambahan</label>
                    <textarea class="form-control form-control-sm" name="deskripsi" rows="3" placeholder="Tulis catatan deskripsi mengenai kategori ini..."></textarea>
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
    window.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#tableBarang')) {
                $('#tableBarang').DataTable().destroy();
            }
            $('#tableBarang').DataTable({
                responsive: true,
                pageLength: 10,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                }
            });

            if ($.fn.DataTable.isDataTable('#tableKategori')) {
                $('#tableKategori').DataTable().destroy();
            }
            $('#tableKategori').DataTable({
                responsive: true,
                pageLength: 5,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                }
            });
        });
    });
</script>
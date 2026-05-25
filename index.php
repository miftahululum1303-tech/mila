<?php
// Memuat berkas konfigurasi database utama
require_once 'config/koneksi.php';

// Memuat komponen layout atas (Navbar, Meta Tag, CSS Stylesheet)
include_once 'includes/header.php';

// Memuat komponen navigasi kiri (Sidebar Menu)
include_once 'includes/sidebar.php';

// --- ARSITEKTUR DYNAMIC PAGE LOADER BERBASIS PARAMETER PAGE ---
// Menangkap parameter '?page=' dari URL secara aman
$page = isset($_GET['page']) ? $_GET['page'] : '';

switch ($page) {
    case 'profil':
        include 'pages/profil.php';
        break;
    // Master Data
    case 'barang':
    case 'supplier':
    case 'safety-stock':
        include 'pages/master.php';
        break;
    // Transaksi
    case 'barang-masuk':
    case 'barang-keluar':
    case 'retur-log':
        include 'pages/transaksi.php';
        break;
    // Laporan
    case 'stok-opname':
    case 'mutasi-stok':
    case 'peramalan':
        include 'pages/laporan.php';
        break;
    // Pengaturan
    case 'user':
        include 'pages/master.php'; // Atau buat file khusus user.php jika terpisah
        break;
    default:
        include 'pages/profil.php';
        break;
}

// Memuat komponen layout bawah (Footer Script, JS Engine)
include_once 'includes/footer.php';

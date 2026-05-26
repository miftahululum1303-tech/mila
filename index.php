<?php
session_start();

if (!isset($_SESSION['login'])) {
    header('Location:auth/login.php');
    exit();
}

include 'config/koneksi.php';

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
case 'dashboard':
include 'pages/dashboard.php';
break;
// Data Barang
case 'barang':
include 'pages/barang.php';
break;
// Supplier
case 'supplier':
include 'pages/supplier.php';
break;
// Barang Masuk
case 'barang-masuk':
include 'pages/barang_masuk.php';
break;
// Barang Keluar
case 'barang-keluar':
include 'pages/barang_keluar.php';
break;
// Laporan
case 'laporan':
include 'pages/laporan.php';
break;
// User Management
case 'user':
include 'pages/user.php';
break;
default:
include 'pages/dashboard.php';
break;
}

// Memuat komponen layout bawah (Footer Script, JS Engine)
include_once 'includes/footer.php';

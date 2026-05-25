<?php
// Konfigurasi Database
$host     = "localhost";
$username = "root";
$password = "";
$database = "mila";

// Koneksi menggunakan MySQLi Procedural
$koneksi = mysqli_connect($host, $username, $password, $database);

// Validasi Koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set timezone agar sinkron dengan server lokal
date_default_timezone_set('Asia/Jakarta');
?>
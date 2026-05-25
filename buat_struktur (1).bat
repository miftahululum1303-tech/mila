@echo off
setlocal
cd /d %~dp0

:: Membuat sub-folder langsung di direktori aktif
echo Membuat sub-folder proyek...
mkdir config
mkdir includes
mkdir pages
mkdir assets
mkdir assets\css
mkdir assets\js
mkdir assets\img
mkdir assets\foto
:: Membuat file kosong di root dan sub-folder
echo Membuat file proyek...
type nul > index.php
type nul > config\koneksi.php

:: File di folder includes
type nul > includes\header.php
type nul > includes\sidebar.php
type nul > includes\footer.php

:: File di folder pages
type nul > pages\profil.php
type nul > pages\master.php
type nul > pages\transaksi.php
type nul > pages\laporan.php

:: File di folder assets
type nul > assets\css\style.css
type nul > assets\js\script.js
type nul > assets\img\logo.png

echo.
echo Struktur file dan folder berhasil dibuat langsung di sini!
pause
<?php
// koneksi.php

$host = "localhost"; // Ganti jika host database Anda berbeda
$user = "root";      // Ganti dengan username database Anda
$pass = "";          // Ganti dengan password database Anda (kosong jika tidak ada)
$db   = "katalog_buku_pribadi1"; // Ganti dengan nama database yang Anda buat

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set karakter set ke utf8mb4 (disarankan)
$conn->set_charset("utf8mb4");
?>
<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_buku = $_GET['id'];

    // Hapus prepared statement, gunakan kueri langsung
    // SANGAT BERISIKO SQL INJECTION di sini!
    $sql_delete = "DELETE FROM Buku WHERE id_buku = " . $id_buku;

    if ($conn->query($sql_delete) === TRUE) { // Jalankan kueri langsung
        header("Location: index.php?status=deleted");
        exit();
    } else {
        // Handle error, misalnya buku tidak ditemukan atau ada kendala relasi
        // urlencode masih digunakan untuk mengamankan string di URL
        header("Location: index.php?status=error&message=" . urlencode($conn->error));
        exit();
    }
} else {
    header("Location: index.php"); // Redirect jika tidak ada ID
    exit();
}

$conn->close();
?>
<?php
include 'koneksi.php'; // Include file koneksi database

// --- Operasi Agregasi ---
$total_buku = 0;
$rata_rata_rating = 0;
$total_halaman = 0;
$rating_tertinggi = 0;
$rating_terendah = 0;

$result_agg = $conn->query("SELECT COUNT(*) AS total_buku,
                                   AVG(rating_pribadi) AS rata_rata_rating,
                                   SUM(jumlah_halaman) AS total_halaman,
                                   MAX(rating_pribadi) AS rating_tertinggi,
                                   MIN(rating_pribadi) AS rating_terendah
                            FROM Buku");

if ($result_agg && $result_agg->num_rows > 0) {
    $row_agg = $result_agg->fetch_assoc();
    $total_buku = $row_agg['total_buku'];
    $rata_rata_rating = number_format($row_agg['rata_rata_rating'], 2); // Format 2 desimal
    $total_halaman = $row_agg['total_halaman'];
    $rating_tertinggi = $row_agg['rating_tertinggi'];
    $rating_terendah = $row_agg['rating_terendah'];
}

// --- Ambil Data Buku dari View ---
$sql_buku = "SELECT * FROM view_buku_lengkap ORDER BY judul_buku ASC";
$result_buku = $conn->query($sql_buku);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku Pribadi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 1200px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1, h2 { color: #333; }
        .buttons { margin-bottom: 20px; }
        .buttons a {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .buttons a:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .agregasi-box {
            background-color: #e9ecef;
            border-left: 5px solid #007bff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .agregasi-box p { margin: 5px 0; }
        .action-links a { margin-right: 10px; text-decoration: none; color: #007bff; }
        .action-links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Katalog Buku Pribadi</h1>

        <div class="buttons">
            <a href="tambah_buku.php">Tambah Buku Baru</a>
            <a href="tambah_penulis.php">Tambah Penulis Baru</a>
            <a href="log_aktivitas.php">Lihat Log Aktivitas</a>
        </div>

        <h2>Ringkasan Koleksi</h2>
        <div class="agregasi-box">
            <p><strong>Total Buku:</strong> <?php echo $total_buku; ?></p>
            <p><strong>Rata-rata Rating Pribadi:</strong> <?php echo $rata_rata_rating; ?></p>
            <p><strong>Total Jumlah Halaman:</strong> <?php echo $total_halaman; ?></p>
            <p><strong>Rating Pribadi Tertinggi:</strong> <?php echo $rating_tertinggi; ?></p>
            <p><strong>Rating Pribadi Terendah:</strong> <?php echo $rating_terendah; ?></p>
        </div>

        <h2>Daftar Buku</h2>
        <?php if ($result_buku && $result_buku->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Tahun Terbit</th>
                        <th>ISBN</th>
                        <th>Halaman</th>
                        <th>Genre</th>
                        <th>Rating</th>
                        <th>Negara Asal Penulis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while($row = $result_buku->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['judul_buku']; ?></td>
                            <td><?php echo $row['nama_penulis']; ?></td>
                            <td><?php echo $row['tahun_terbit']; ?></td>
                            <td><?php echo $row['isbn']; ?></td>
                            <td><?php echo $row['jumlah_halaman']; ?></td>
                            <td><?php echo $row['genre']; ?></td>
                            <td><?php echo $row['rating_pribadi']; ?></td>
                            <td><?php echo $row['negara_asal']; ?></td>
                            <td class="action-links">
                                <a href="edit_buku.php?id=<?php echo $row['id_buku']; ?>">Edit</a> |
                                <a href="hapus_buku.php?id=<?php echo $row['id_buku']; ?>" onclick="return confirm('Yakin ingin menghapus buku ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Belum ada buku dalam katalog.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close(); // Tutup koneksi database
?>
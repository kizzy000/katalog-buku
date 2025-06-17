<?php
include 'koneksi.php';

$message = ''; // Untuk pesan sukses/error

// Ambil daftar penulis untuk dropdown (tetap aman karena tidak ada input pengguna langsung)
$sql_penulis = "SELECT id_penulis, nama_penulis FROM Penulis ORDER BY nama_penulis ASC";
$result_penulis = $conn->query($sql_penulis);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul_buku = $_POST['judul_buku'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $isbn = $_POST['isbn'];
    $jumlah_halaman = $_POST['jumlah_halaman'];
    $id_penulis = $_POST['id_penulis'];
    $genre = $_POST['genre'];
    $rating_pribadi = $_POST['rating_pribadi'];

    // Validasi sederhana
    if (empty($judul_buku) || empty($id_penulis)) {
        $message = "Judul buku dan Penulis wajib diisi.";
    } else {
        // Hapus prepared statement, gunakan kueri langsung
        // SANGAT BERISIKO SQL INJECTION di sini!
        // Pastikan nilai string diapit tanda kutip, dan angka tidak.
        $sql_insert = "INSERT INTO Buku (judul_buku, tahun_terbit, isbn, jumlah_halaman, id_penulis, genre, rating_pribadi) VALUES (
            '" . $judul_buku . "',
            " . ($tahun_terbit ? $tahun_terbit : 'NULL') . ",
            '" . $isbn . "',
            " . ($jumlah_halaman ? $jumlah_halaman : 'NULL') . ",
            " . $id_penulis . ",
            '" . $genre . "',
            " . ($rating_pribadi ? $rating_pribadi : 'NULL') . "
        )";

        if ($conn->query($sql_insert) === TRUE) { // Jalankan kueri langsung
            $message = "Buku berhasil ditambahkan!";
            // Redirect ke halaman utama setelah sukses
            header("Location: index.php");
            exit();
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku Baru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover { background-color: #218838; }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            <?php echo $message ? (strpos($message, 'Error') !== false ? 'background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;' : 'background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;') : ''; ?>
        }
        .back-link { display: block; margin-top: 20px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Buku Baru</h1>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="tambah_buku.php" method="POST">
            <label for="judul_buku">Judul Buku:</label>
            <input type="text" id="judul_buku" name="judul_buku" required>

            <label for="id_penulis">Penulis:</label>
            <select id="id_penulis" name="id_penulis" required>
                <option value="">Pilih Penulis</option>
                <?php
                if ($result_penulis && $result_penulis->num_rows > 0) {
                    while ($row_penulis = $result_penulis->fetch_assoc()) {
                        // Hapus htmlspecialchars
                        echo "<option value='" . $row_penulis['id_penulis'] . "'>" . $row_penulis['nama_penulis'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Belum ada penulis. Tambahkan dulu!</option>";
                }
                ?>
            </select>

            <label for="tahun_terbit">Tahun Terbit:</label>
            <input type="number" id="tahun_terbit" name="tahun_terbit" min="1500" max="<?php echo date('Y'); ?>">

            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn">

            <label for="jumlah_halaman">Jumlah Halaman:</label>
            <input type="number" id="jumlah_halaman" name="jumlah_halaman" min="1">

            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre">

            <label for="rating_pribadi">Rating Pribadi (1.0 - 5.0):</label>
            <input type="number" id="rating_pribadi" name="rating_pribadi" step="0.1" min="1.0" max="5.0">

            <input type="submit" value="Tambah Buku">
        </form>
        <a href="index.php" class="back-link">Kembali ke Katalog</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
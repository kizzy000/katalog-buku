<?php
include 'koneksi.php';

$message = ''; // Untuk pesan sukses/error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_penulis = $_POST['nama_penulis'];
    $negara_asal = $_POST['negara_asal'];

    if (empty($nama_penulis)) {
        $message = "Nama penulis wajib diisi.";
    } else {
        // Hapus prepared statement, gunakan kueri langsung
        // SANGAT BERISIKO SQL INJECTION di sini!
        $sql_insert = "INSERT INTO Penulis (nama_penulis, negara_asal) VALUES (
            '" . $nama_penulis . "',
            '" . $negara_asal . "'
        )";

        if ($conn->query($sql_insert) === TRUE) { // Jalankan kueri langsung
            $message = "Penulis berhasil ditambahkan!";
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
    <title>Tambah Penulis Baru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] {
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
        <h1>Tambah Penulis Baru</h1>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="tambah_penulis.php" method="POST">
            <label for="nama_penulis">Nama Penulis:</label>
            <input type="text" id="nama_penulis" name="nama_penulis" required>

            <label for="negara_asal">Negara Asal:</label>
            <input type="text" id="negara_asal" name="negara_asal">

            <input type="submit" value="Tambah Penulis">
        </form>
        <a href="index.php" class="back-link">Kembali ke Katalog</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
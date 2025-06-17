<?php
include 'koneksi.php';

$sql_log = "SELECT * FROM LogAktivitas ORDER BY waktu_aksi DESC";
$result_log = $conn->query($sql_log);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .back-link { display: block; margin-top: 20px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Log Aktivitas</h1>

        <?php if ($result_log && $result_log->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Aksi</th>
                        <th>Waktu Aksi</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while($row = $result_log->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['aksi']; ?></td>
                            <td><?php echo $row['waktu_aksi']; ?></td>
                            <td><?php echo $row['deskripsi']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada log aktivitas.</p>
        <?php endif; ?>
        <a href="index.php" class="back-link">Kembali ke Katalog</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
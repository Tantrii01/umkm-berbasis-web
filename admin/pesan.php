<?php
include "../koneksi.php";

// Logika Hapus (Delete)
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pesan WHERE id_pesan = '$id'");
    header("Location: admin_pesan.php");
}

$ambil_pesan = mysqli_query($conn, "SELECT * FROM pesan ORDER BY tanggal_kirim DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Pesan - Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table { width: 90%; margin: 20px auto; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #088178; color: white; }
        .btn-hapus { color: red; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <h2 style="text-align: center; margin-top: 20px;">Daftar Pesan Masuk</h2>
    <table>
        <tr>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Isi Pesan</th>
            <th>Aksi</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($ambil_pesan)): ?>
        <tr>
            <td><?= $row['tanggal_kirim']; ?></td>
            <td><?= $row['nama']; ?></td>
            <td><?= $row['email']; ?></td>
            <td><?= $row['subject']; ?></td>
            <td><?= $row['pesan']; ?></td>
            <td>
                <a href="admin_pesan.php?hapus=<?= $row['id_pesan']; ?>" 
                   onclick="return confirm('Yakin ingin menghapus pesan ini?')" class="btn-hapus">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
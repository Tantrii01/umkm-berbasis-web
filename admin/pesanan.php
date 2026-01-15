<?php
include "../koneksi.php";

// --- LOGIKA UPDATE STATUS ---
if (isset($_POST['update_status'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id_transaksi']);
    $status_baru = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Gunakan kolom 'status' (sesuai gambar database kamu)
    $query_update = mysqli_query($conn, "UPDATE transaksi SET status = '$status_baru' WHERE id_transaksi = '$id'");

    if ($query_update) {
        echo "<script>alert('Status Berhasil Diperbarui!'); window.location.href='pesanan.php';</script>";
    }
    exit;
}

// --- LOGIKA HAPUS ---
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus']);
    // Pastikan query hapus menggunakan id_transaksi yang benar
    $query_hapus = mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi = '$id_hapus'");
    
    if($query_hapus){
        echo "<script>alert('Pesanan Berhasil Dihapus!'); window.location.href='pesanan.php';</script>";
    }
    exit;
}

$data = mysqli_query($conn, "SELECT * FROM transaksi ORDER BY id_transaksi DESC");
?>



<!DOCTYPE html>
<html>
<head>
    <title>Admin - Kelola Pesanan</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <style>
        body { display: flex; background: #f0f2f5; margin:0; font-family: 'Poppins', sans-serif; }
        .sidebar { width: 260px; background: #2c3e50; color: white; min-height: 100vh; position: fixed; }
        .sidebar a { display:block; color:white; padding:15px; text-decoration:none; transition: 0.3s; }
        .sidebar a:hover { background: #34495e; }
        .content { margin-left: 260px; padding: 40px; width: calc(100% - 260px); }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; font-size: 13px; }
        th { background-color: #088178; color: white; }
        .btn-update { 
            background: #088178; color: white; border: none; padding: 6px 12px; 
            cursor: pointer; border-radius: 4px; font-weight: bold;
        }
        .btn-hapus { background: #ff4d4d; color: white; padding: 8px 10px; border-radius: 4px; text-decoration: none; font-size: 11px; }
        .btn-detail { background: #3498db; color: white; padding: 8px 10px; border-radius: 4px; text-decoration: none; font-size: 11px; }
        select { padding: 5px; border-radius: 4px; border: 1px solid #ccc; background: #f9f9f9; }
        
        /* Warna Label Status */
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .badge-proses { background: #e3f2fd; color: #1976d2; }
        .badge-dikemas { background: #fff3e0; color: #f57c00; }
        .badge-dikirim { background: #e8f5e9; color: #2e7d32; }
    </style>
</head>
<body>

<div class="sidebar">
    <div style="padding: 20px;"><h2>Admin Ngelokal</h2></div>
    <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="produk.php"><i class="fas fa-box"></i> Produk</a>
    <a href="pesan.php"><i class="fas fa-box"></i> pesan</a>
    <a href="pesanan.php" style="background:#088178;"><i class="fas fa-shopping-cart"></i> Pesanan</a>
    <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="content">
    <div class="card">
        <h2>Daftar Pesanan & Status</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Update Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
    <?php while($p = mysqli_fetch_assoc($data)) { ?>
    <tr>
        <td>#<?= $p['id_transaksi'] ?></td>
        <td><strong><?= $p['nama_pelanggan'] ?></strong></td>
        <td>Rp<?= number_format($p['total_harga']) ?></td>
        <td>
            <?php 
                // Ganti status_pesanan menjadi status
                $st = $p['status'];
                if($st == 'Diproses') echo "<span class='badge badge-proses'>Diproses</span>";
                elseif($st == 'Dikemas') echo "<span class='badge badge-dikemas'>Dikemas</span>";
                elseif($st == 'Dikirim') echo "<span class='badge badge-dikirim'>Dikirim</span>";
                else echo "<span class='badge'>$st</span>";
            ?>
        </td>
        <td>
            <form action="pesanan.php" method="POST" style="display: flex; gap: 5px;">
                <input type="hidden" name="id_transaksi" value="<?= $p['id_transaksi'] ?>">
                <select name="status">
                    <option value="Diproses" <?= ($p['status'] == 'Diproses') ? 'selected' : '' ?>>Diproses</option>
                    <option value="Dikemas" <?= ($p['status'] == 'Dikemas') ? 'selected' : '' ?>>Dikemas</option>
                    <option value="Dikirim" <?= ($p['status'] == 'Dikirim') ? 'selected' : '' ?>>Dikirim</option>
                </select>
                <button type="submit" name="update_status" class="btn-update">OK</button>
            </form>
        </td>
        <td>
            <div style="display: flex; gap: 5px;">
                <a href="detail_pesanan.php?id=<?= $p['id_transaksi'] ?>" class="btn-detail"><i class="fas fa-eye"></i></a>
                <a href="pesanan.php?hapus=<?= $p['id_transaksi'] ?>" class="btn-hapus" onclick="return confirm('Hapus pesanan ini?')">
                    <i class="fas fa-trash"></i>
                </a>
            </div>
        </td>
    </tr>
    <?php } ?>
</tbody>
        </table>
    </div>
</div>

</body>
</html>
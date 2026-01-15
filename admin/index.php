<?php
session_start();
include "../koneksi.php";

// 1. Menghitung Total Produk
$q_produk = mysqli_query($conn, "SELECT * FROM produk");
$total_produk = mysqli_num_rows($q_produk);

// 2. Menghitung Pesanan Baru (Pending/Diproses)
// Menggunakan kolom 'status' sesuai database kamu
$q_pending = mysqli_query($conn, "SELECT * FROM transaksi WHERE status = 'Diproses'");
$pesanan_pending = mysqli_num_rows($q_pending);

// 3. Menghitung TOTAL SEMUA PESANAN (Variabel yang bikin error di baris 76)
$q_total_pesanan = mysqli_query($conn, "SELECT * FROM transaksi");
$total_pesanan = mysqli_num_rows($q_total_pesanan); 

$q_pesan = mysqli_query($conn, "SELECT * FROM pesan");
$total_pesan = mysqli_num_rows($q_pesan);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Ngelokal</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="style.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Spartan', sans-serif; margin: 0; }
        #admin-aside { width: 250px; background: #222; height: 100vh; position: fixed; color: white; padding-top: 20px; }
        #admin-aside h2 { text-align: center; color: #088178; margin-bottom: 30px; }
        #admin-aside a { display: block; color: white; padding: 15px 25px; text-decoration: none; transition: 0.3s; }
        #admin-aside a:hover { background: #088178; }
        #admin-aside a i { margin-right: 10px; }
        
        #main-content { margin-left: 250px; padding: 30px; }
        .card-container { display: flex; gap: 20px; margin-top: 20px; }
        .card { background: white; padding: 20px; border-radius: 10px; flex: 1; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-bottom: 4px solid #088178; }
        .card h3 { color: #555; font-size: 14px; text-transform: uppercase; margin: 0; }
        .card p { font-size: 28px; font-weight: bold; margin: 10px 0 0 0; color: #222; }
        
        /* Badge Notifikasi Sidebar */
        .badge { background: #ff4d4d; color: white; padding: 2px 8px; border-radius: 20px; font-size: 11px; float: right; }
    </style>
</head>
<body>

    <div id="admin-aside">
        <h2>Ngelokal</h2>
        <a href="index.php"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="produk.php"><i class="fas fa-box"></i> produk</a>
          <a href="pesan.php"><i class="fas fa-box"></i> pesan</a>
        <a href="pesanan.php">
            <i class="fas fa-shopping-cart"></i> Pesanan 
            <?php if($pesanan_pending > 0): ?>
                <span class="badge"><?= $pesanan_pending ?></span>
            <?php endif; ?>
        </a>
        <a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> Lihat Toko</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    
    </div>
    </a>
    
    <a href="pesan.php">
        <i class="fas fa-envelope"></i> Pesan Masuk
        <?php if($total_pesan > 0): ?>
            <span class="badge" style="background: #3498db;"><?= $total_pesan ?></span>
        <?php endif; ?>
    </a>
    
    <a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> Lihat Toko</a>
   
</div>
    <div id="main-content">
        <h1>Selamat Datang, Admin!</h1>
        <p>Ringkasan performa toko Ngelokal hari ini.</p>

        <div class="card-container">
            <div class="card-container">
    <div class="card">
        <h3>Total Produk</h3>
        <p><?= $total_produk ?></p>
    </div>
    <div class="card" style="border-bottom-color: #f39c12;">
        <h3>Pesanan Baru</h3>
        <p style="color: #f39c12;"><?= $pesanan_pending ?></p>
    </div>
    
    <div class="card" style="border-bottom-color: #3498db;">
        <h3>Pesan Masuk</h3>
        <p style="color: #3498db;"><?= $total_pesan ?></p>
    </div>

    <div class="card" style="border-bottom-color: #27ae60;">
        <h3>Total Transaksi</h3>
        <p><?= $total_pesanan ?></p>
    </div>
</div>
           
                
        <div style="margin-top: 40px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3>Aktivitas Cepat</h3>
            <p>Gunakan menu di samping untuk mengelola inventaris produk atau memproses pesanan pelanggan yang masuk secara real-time.</p>
        </div>
    </div>

</body>
</html>
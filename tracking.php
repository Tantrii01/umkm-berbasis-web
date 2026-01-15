<?php
session_start();
include "koneksi.php";

// 1. Proteksi Login - Menggunakan id_customer (Pastikan session ini dibuat saat login)
if (!isset($_SESSION['id_customer'])) {
    echo "<script>alert('Silahkan login terlebih dahulu!'); window.location='login.php';</script>";
    exit;
}

$id_cust = $_SESSION['id_customer'];

// 2. AMBIL DATA TERBARU - Query ini harus mengambil status_pesanan yang diupdate admin
$query = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_customer = '$id_cust' ORDER BY id_transaksi DESC LIMIT 1");
$row = mysqli_fetch_assoc($query);

// 3. LOGIKA SINKRONISASI - Membersihkan spasi atau perbedaan huruf besar/kecil
// Ganti 'status_pesanan' dengan nama kolom yang ada di database kamu
$status_p = isset($row['status']) ? $row['status'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Pesanan - Ngelokal</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fce4ec; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; font-family: 'Poppins', sans-serif; }
        .tracking-card { width: 380px; background: #fff; border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); overflow: hidden; padding-bottom: 20px; }
        .card-header-pink { background: #f8bbd0; padding: 20px; display: flex; align-items: center; gap: 12px; color: #444; }
        .circle-id { background: #fff; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; }
        .header-icons { margin-left: auto; display: flex; gap: 10px; color: #888; }
        .tracking-content { padding: 20px; }
        
        /* Badge Status (Lunas/Pending) */
        .status-badge { background: #2e7d32; color: #fff; padding: 4px 15px; border-radius: 20px; font-size: 11px; float: right; font-weight: 600; text-transform: uppercase; }
        
        .info-section { margin-bottom: 20px; }
        .info-section label { color: #999; font-size: 11px; display: block; }
        .info-section p { margin: 0; font-size: 14px; font-weight: 600; color: #333; }
        .trx-id { color: #088178 !important; }

        /* Stepper Logic CSS */
        .stepper-container { display: flex; justify-content: space-between; align-items: center; margin: 30px 0; position: relative; }
        .stepper-container::before { content: ""; position: absolute; top: 15px; left: 10%; right: 10%; height: 2px; background: #eee; z-index: 1; }
        .step-item { position: relative; z-index: 2; text-align: center; flex: 1; }
        .step-dot { width: 12px; height: 12px; background: #e0e0e0; border-radius: 50%; margin: 0 auto 10px; border: 3px solid #fff; box-shadow: 0 0 0 2px #eee; }
        .step-item i { display: block; font-size: 18px; color: #ccc; margin-bottom: 5px; }
        .step-item p { font-size: 10px; margin: 0; color: #aaa; font-weight: 600; }

        /* Warna Hijau jika Status AKTIF */
        .step-item.active .step-dot { background: #4caf50; box-shadow: 0 0 0 2px #4caf50; }
        .step-item.active i { color: #4caf50; }
        .step-item.active p { color: #4caf50; }

        .footer-action { border-top: 1px dashed #eee; padding: 15px 20px 0; display: flex; align-items: center; justify-content: space-between; }
        .status-aktif { display: flex; align-items: center; gap: 8px; color: #444; font-size: 12px; }
        .btn-shop { background: #088178; color: #fff; padding: 8px 18px; border-radius: 20px; text-decoration: none; font-size: 11px; font-weight: 600; transition: 0.3s; }
    </style>
</head>
<body>

    <div class="tracking-card">
        <div class="card-header-pink">
            <div class="circle-id"><?= $row ? $row['id_transaksi'] : '0'; ?></div>
            <strong>Lacak Pesanan</strong>
            <div class="header-icons">
                <i class="fas fa-home" onclick="window.location.href='index.php'" style="cursor:pointer;"></i>
            </div>
        </div>

        <div class="tracking-content">
            
            <?php if ($row) : ?>
                <span class="status-badge"><?= $row['status']; ?></span>
                
                <div class="info-section">
                    <label>NOMOR TRANSAKSI</label>
                    <p class="trx-id">#TRX-<?= $row['id_transaksi']; ?></p>
                </div>

                <div class="info-section">
                    <label>WAKTU TRANSAKSI</label>
                    <p><?= date('d M Y, H:i', strtotime($row['tanggal'])); ?> WIB</p>
                </div>

                <div class="stepper-container">
                    <div class="step-item active">
                        <div class="step-dot"></div>
                        <i class="fas fa-sync-alt"></i>
                        <p>Proses</p>
                    </div>

                    <div class="step-item <?= ($status_p == 'Dikemas' || $status_p == 'Dikirim') ? 'active' : ''; ?>">
                        <div class="step-dot"></div>
                        <i class="fas fa-box"></i>
                        <p>Dikemas</p>
                    </div>

                    <div class="step-item <?= ($status_p == 'Dikirim') ? 'active' : ''; ?>">
                        <div class="step-dot"></div>
                        <i class="fas fa-truck"></i>
                        <p>Dikirim</p>
                    </div>
                </div>

                <div class="footer-action">
                    <div class="status-aktif">
                        <i class="fas fa-info-circle" style="color:#088178"></i>
                        <span>Status: <strong><?= $status_p ? $status_p : 'Diproses'; ?></strong></span>
                    </div>
                    <a href="shop.php" class="btn-shop">Shop Lagi</a>
                </div>

            <?php else : ?>
                <div style="text-align: center; padding: 30px;">
                    <i class="fas fa-shopping-bag" style="font-size: 50px; color: #eee; margin-bottom: 15px;"></i>
                    <p style="color: #999;">Kamu belum punya pesanan nih.</p>
                    <a href="shop.php" class="btn-shop" style="display:inline-block; margin-top:10px;">Mulai Belanja</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
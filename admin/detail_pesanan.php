<?php
session_start();
include "../koneksi.php";

// 1. Cek Login Admin (Opsional: tambahkan jika kamu punya session admin)
// if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

// 2. Validasi ID agar tidak error jika ID tidak ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Pesanan tidak valid'); window.location='pesanan.php';</script>";
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// 3. Ambil data induk dari tabel transaksi
$query_transaksi = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = '$id'");
$d = mysqli_fetch_assoc($query_transaksi);

// 4. Ambil data rincian barang dari tabel detail_transaksi
$query_produk = mysqli_query($conn, "SELECT * FROM detail_transaksi WHERE id_transaksi = '$id'");

if (!$d) {
    echo "<script>alert('Pesanan tidak ditemukan'); window.location='pesanan.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?= $id ?> - Ngelokal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <style>
        body { font-family: 'Spartan', sans-serif; background: #f4f7f6; padding: 20px; }
        .detail-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); max-width: 900px; margin: auto; }
        .header-detail { border-bottom: 2px solid #088178; padding-bottom: 15px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        
        /* Badge Status */
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; color: white; }
        .bg-proses { background: #f39c12; } /* Orange */
        .bg-kirim { background: #27ae60; }   /* Hijau */
        .bg-selesai { background: #2980b9; } /* Biru */

        .info-section { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px; }
        .info-box h4 { color: #088178; margin-bottom: 10px; border-left: 4px solid #088178; padding-left: 10px; }
        .info-box p { margin: 5px 0; font-size: 14px; line-height: 1.6; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #088178; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        
        .total-row { font-size: 20px; font-weight: bold; color: #088178; text-align: right; margin-top: 25px; padding-top: 15px; border-top: 2px solid #eee; }
        
        .btn-wa { background: #25D366; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px; }
        .btn-wa:hover { background: #128C7E; }
        
        .btn-back { display: inline-block; margin-top: 20px; text-decoration: none; color: #666; font-weight: 600; transition: 0.3s; }
        .btn-back:hover { color: #088178; }

        @media (max-width: 600px) {
            .info-section { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="detail-card">
        <div class="header-detail">
            <div>
                <h2 style="margin:0;">Pesanan #<?= $id ?></h2>
                <small style="color:#888;"><i class="far fa-calendar-alt"></i> <?= date('d F Y, H:i', strtotime($d['tanggal'])) ?></small>
            </div>
            <?php 
                $status_class = 'bg-proses';
                if($d['status'] == 'Dikirim') $status_class = 'bg-kirim';
                if($d['status'] == 'Selesai') $status_class = 'bg-selesai';
            ?>
            <span class="status-badge <?= $status_class ?>"><?= $d['status'] ?></span>
        </div>

        <div class="info-section">
            <div class="info-box">
                <h4><i class="fas fa-user"></i> Info Pelanggan</h4>
                <p><strong>Nama:</strong> <?= $d['nama_pelanggan'] ?></p>
                <p><strong>No. HP:</strong> <?= $d['no_hp'] ?> 
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $d['no_hp']) ?>" target="_blank" class="btn-wa">
                        <i class="fab fa-whatsapp"></i> Chat
                    </a>
                </p>
                <p><strong>Alamat:</strong> <?= $d['alamat'] ?></p>
            </div>
            <div class="info-box">
                <h4><i class="fas fa-credit-card"></i> Pembayaran</h4>
                <p><strong>Metode:</strong> <?= $d['metode_bayar'] ?></p>
                <p><strong>Total Tagihan:</strong> Rp <?= number_format($d['total_harga'], 0, ',', '.') ?></p>
                <p><strong>Catatan:</strong> <span style="color:#888;">Tidak ada catatan</span></p>
            </div>
        </div>

        <h4><i class="fas fa-shopping-basket"></i> Daftar Barang</h4>
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Size</th>
                    <th>Harga</th>
                    <th style="text-align:center;">Qty</th>
                    <th>Subtotal</th>            </tr            </thead>
            <tbody>
                <?php 
                $cek_data = mysqli_num_rows($query_produk);
                if($cek_data > 0):
                    while($item = mysqli_fetch_assoc($query_produk)): 
                ?>
                <tr>
                    <td><strong><?= $item['nama_produk'] ?></strong></td>
                    <td><?= strtoupper($item['size']) ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td style="text-align:center;"><?= $item['qty'] ?></td>
                    <td>Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?></td>
                </tr>
                <?php 
                    endwhile; 
                else:
                ?>
                <tr>
                    <td colspan="5" style="text-align:center; color:red; padding: 30px;">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        Rincian produk tidak tersedia untuk pesanan lama ini.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="total-row">
            Total Bayar: Rp <?= number_format($d['total_harga'], 0, ',', '.') ?>
        </div>
        
        <a href="pesanan.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan</a>
    </div>

</body>
</html>
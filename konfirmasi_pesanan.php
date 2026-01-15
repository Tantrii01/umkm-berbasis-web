<?php
session_start();
include "koneksi.php";

// Pastikan user login
if(!isset($_SESSION['id_user'])){
    echo "Silakan login terlebih dahulu.";
    exit;
}

$id_user = $_SESSION['id_user'];

// Ambil pesanan user
$query = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_user='$id_user' ORDER BY id_pesanan DESC");

?>

<h2>Pesanan Saya</h2>

<?php
if (!$query || mysqli_num_rows($query) == 0) {
    echo "<p>Belum ada pesanan.</p>";
} else {
    while ($p = mysqli_fetch_assoc($query)) {
        // Gunakan nama kolom sesuai database, ganti jika berbeda
        $id_pesanan = $p['id_pesanan'];
        $total      = $p['total'] ?? 0; // jika kolom 'total' di DB
        $pembayaran = $p['metode'] ?? '-'; // jika kolom 'metode' di DB
        $status     = $p['status'] ?? '-';
?>
    <div style="border:1px solid #000; padding:10px; margin:10px;">
        <p><b>ID Pesanan:</b> <?= $id_pesanan; ?></p>
        <p><b>Total:</b> Rp <?= number_format($total); ?></p>
        <p><b>Pembayaran:</b> <?= strtoupper($pembayaran); ?></p>
        <p><b>Status:</b> <?= strtoupper($status); ?></p>
        <p><a href="detail_pesanan.php?id=<?= $id_pesanan ?>">Lihat Detail</a></p>
    </div>
<?php
    }
}
?>

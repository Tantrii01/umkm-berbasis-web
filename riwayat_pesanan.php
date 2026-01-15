<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php"); // redirect kalau belum login
    exit;
}

$id_user = $_SESSION['id_user'];

include "koneksi.php"; // pastikan path benar

$sql = "SELECT * FROM pesanan WHERE id_user='$id_user' ORDER BY tgl_pesanan DESC";
$result = mysqli_query($koneksi, $sql);
?>

<h2>Riwayat Pesanan Saya</h2>

<?php while($pesanan = mysqli_fetch_assoc($result)): ?>
    <hr>
    <p>Nomor Pesanan: <b><?= $pesanan['id_pesanan'] ?></b></p>
    <p>Tanggal: <?= $pesanan['tgl_pesanan'] ?></p>
    <p>Status: <?= $pesanan['status'] ?></p>
    <p>Metode Pembayaran: <?= $pesanan['metode_pembayaran'] ?></p>

    <?php
    // ambil detail per pesanan
    $id_pesanan = $pesanan['id_pesanan'];
    $sql_detail = "SELECT dp.qty, dp.harga, p.nama_produk 
                   FROM detail_pesanan dp 
                   JOIN produk p ON dp.id_produk = p.id_produk 
                   WHERE dp.id_pesanan='$id_pesanan'";
    $result_detail = mysqli_query($koneksi, $sql_detail);
    ?>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
        <?php 
        $total = 0;
        while($row = mysqli_fetch_assoc($result_detail)) {
            $subtotal = $row['qty'] * $row['harga'];
            $total += $subtotal;
            echo "<tr>
                    <td>{$row['nama_produk']}</td>
                    <td>{$row['qty']}</td>
                    <td>{$row['harga']}</td>
                    <td>$subtotal</td>
                  </tr>";
        } 
        ?>
        <tr>
            <td colspan="3" align="right"><b>Total</b></td>
            <td><b><?= $total ?></b></td>
        </tr>
    </table>
<?php endwhile; ?>

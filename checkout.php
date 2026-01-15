<?php
include "koneksi.php";

$id_produk = $_GET['id'];
$p = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id_produk'")
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama    = $_POST['nama'];
    $alamat  = $_POST['alamat'];
    $hp      = $_POST['hp'];
    $total   = $p['harga'];

    // simpan ke pesanan
    mysqli_query($conn, "
        INSERT INTO pesanan (nama, alamat, no_hp, total_harga, status)
        VALUES ('$nama','$alamat','$hp','$total','pending')
    ");

    $id_pesanan = mysqli_insert_id($conn);

    // simpan ke detail pesanan
    mysqli_query($conn, "
        INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah, harga)
        VALUES ('$id_pesanan','$id_produk',1,'$total')
    ");

    header("Location: sukses.php");
    exit;
}
?>

<h2>Checkout</h2>

<form method="post">
    <p>Produk: <b><?= $p['nama_produk']; ?></b></p>
    <p>Harga: Rp <?= number_format($p['harga']); ?></p>

    <input type="text" name="nama" placeholder="Nama" required><br><br>
    <textarea name="alamat" placeholder="Alamat" required></textarea><br><br>
    <input type="text" name="hp" placeholder="No HP" required><br><br>

    <button type="submit">Pesan Sekarang</button>
</form>

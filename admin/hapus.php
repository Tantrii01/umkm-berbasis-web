<?php
include "../koneksi.php";

$id = $_GET['id'];

$hapus = mysqli_query($conn, "DELETE FROM produk WHERE id_produk='$id'");

if ($hapus) {
    header("Location: produk.php");
    exit;
} else {
    echo "Gagal menghapus produk";
}

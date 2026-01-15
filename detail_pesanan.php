<?php
session_start();
include "koneksi.php";

if (isset($_POST['proses_checkout'])) {
    $id_cust = $_SESSION['id_customer'];
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $hp     = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $metode = mysqli_real_escape_string($conn, $_POST['metode']);
    
    // Ambil data keranjang dari input hidden
    $cart_json = $_POST['cart_data'];
    $cart_items = json_decode($cart_json, true);

    if (empty($cart_items)) {
        echo "<script>alert('Keranjang kosong!'); window.location='cart.php';</script>";
        exit;
    }

    // Hitung Total
    $total_harga = 0;
    foreach ($cart_items as $item) {
        $price = $item['price'] ?? $item['harga'] ?? 0;
        $qty   = $item['qty'] ?? 1;
        $total_harga += ($price * $qty);
    }

    $tgl = date('Y-m-d H:i:s');

    // 1. Simpan ke tabel induk (transaksi)
    $sql = "INSERT INTO transaksi (id_customer, nama_pelanggan, no_hp, alamat, metode_bayar, total_harga, status, tanggal) 
            VALUES ('$id_cust', '$nama', '$hp', '$alamat', '$metode', '$total_harga', 'Diproses', '$tgl')";

    if (mysqli_query($conn, $sql)) {
        $id_baru = mysqli_insert_id($conn); // Ambil ID yang baru saja dibuat

        // 2. Simpan rincian ke tabel detail_transaksi
        foreach ($cart_items as $item) {
            $nama_p  = mysqli_real_escape_string($conn, $item['name']);
            $harga_p = $item['price'] ?? $item['harga'] ?? 0;
            $qty_p   = $item['qty'];
            $size_p  = mysqli_real_escape_string($conn, $item['size']);

            $q_detail = "INSERT INTO detail_transaksi (id_transaksi, nama_produk, harga, qty, size) 
                         VALUES ('$id_baru', '$nama_p', '$harga_p', '$qty_p', '$size_p')";
            
            mysqli_query($conn, $q_detail);
        }

        echo "<script>
                alert('Pesanan Berhasil!');
                localStorage.removeItem('cart'); 
                window.location.href = 'tracking.php';
              </script>";
    } else {
        echo "Error Database: " . mysqli_error($conn);
    }
}
?>
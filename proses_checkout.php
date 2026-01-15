<?php
session_start();
include "koneksi.php";

// Cek apakah koneksi ke database berhasil
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dan tampilkan untuk tes (Debug)
    $id_cust = $_SESSION['id_customer'] ?? 0;
    $nama    = mysqli_real_escape_string($conn, $_POST['nama']);
    $hp      = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat  = mysqli_real_escape_string($conn, $_POST['alamat']);
    $metode  = mysqli_real_escape_string($conn, $_POST['metode']);
    $cart_json = $_POST['cart_data'];
    $cart_items = json_decode($cart_json, true);

    if (empty($cart_items)) {
        die("Error: Data keranjang kosong atau tidak terkirim dari cart.php");
    }

    $total_harga = 0;
    foreach ($cart_items as $item) {
        $price = $item['price'] ?? $item['harga'] ?? 0;
        $qty   = $item['qty'] ?? 1;
        $total_harga += ($price * $qty);
    }

    $tgl = date('Y-m-d H:i:s');

    // INSERT KE TABEL TRANSAKSI
    // PASTIKAN NAMA KOLOM DI BAWAH INI (id_customer, nama_pelanggan, dll) SAMA DENGAN DI DATABASE KAMU
    $sql_transaksi = "INSERT INTO transaksi (id_customer, nama_pelanggan, no_hp, alamat, metode_bayar, total_harga, status, tanggal) 
                      VALUES ('$id_cust', '$nama', '$hp', '$alamat', '$metode', '$total_harga', 'Diproses', '$tgl')";

    if (mysqli_query($conn, $sql_transaksi)) {
        $id_baru = mysqli_insert_id($conn);

        // INSERT KE DETAIL_TRANSAKSI
        foreach ($cart_items as $item) {
            $nama_p  = mysqli_real_escape_string($conn, $item['name']);
            $harga_p = $item['price'] ?? $item['harga'] ?? 0;
            $qty_p   = $item['qty'];
            $size_p  = mysqli_real_escape_string($conn, $item['size'] ?? '-');

            $q_detail = "INSERT INTO detail_transaksi (id_transaksi, nama_produk, harga, qty, size) 
                         VALUES ('$id_baru', '$nama_p', '$harga_p', '$qty_p', '$size_p')";
            
            if (!mysqli_query($conn, $q_detail)) {
                die("Gagal simpan detail: " . mysqli_error($conn));
            }
        }

        echo "<script>
                alert('Berhasil! Pesanan ID: $id_baru');
                localStorage.removeItem('cart'); 
                window.location.href = 'tracking.php';
              </script>";
    } else {
        // Jika muncul tulisan ini, berarti nama kolom di tabel 'transaksi' kamu ada yang salah
        die("Gagal simpan transaksi utama: " . mysqli_error($conn));
    }
} else {
    die("Error: Tombol submit tidak terdeteksi. Pastikan tombol di cart.php punya name='proses_checkout'");
}
?>
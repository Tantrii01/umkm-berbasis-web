<?php
include "koneksi.php";

// simulasi data
$id_user = 1;
$alamat  = "Alamat dummy";
$total   = 150000;

// simpan ke pesanan
mysqli_query($conn, "
  INSERT INTO pesanan (id_user, alamat, total_harga)
  VALUES ($id_user, '$alamat', $total)
");

echo "Pesanan masuk";

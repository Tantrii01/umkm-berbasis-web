<?php
// Memulai session agar sistem tahu session mana yang akan dihapus
session_start();

// Menghapus semua variabel session (id_customer, nama_customer, dll)
session_unset();

// Menghancurkan session yang sedang berjalan
session_destroy();

// Menampilkan pesan sukses dan mengarahkan kembali ke halaman utama
echo "<script>
    alert('Anda telah berhasil logout. Sampai jumpa lagi!');
    window.location.href='index.php';
</script>";
exit();
?>
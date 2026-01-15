<?php
include "koneksi.php";

if (isset($_POST['daftar'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']); 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // SESUAI SCREENSHOT: Tabel 'customers' dan Kolom 'no_hp'
    $cek = mysqli_query($conn, "SELECT * FROM customers WHERE email = '$email' OR no_hp = '$no_hp'");
    
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Email atau nomor HP sudah terdaftar!'); window.location='daftar.php';</script>";
    } else {
        // SESUAI SCREENSHOT: Menggunakan kolom no_hp dan email
        $query = mysqli_query($conn, "INSERT INTO customers (nama, email, no_hp, password) VALUES ('$nama', '$email', '$no_hp', '$password')");
        
        if ($query) {
            echo "<script>alert('Pendaftaran Berhasil! Silahkan Login'); window.location='login.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar - Ngelokal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container { display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #E3E6F3; }
        .form-box { background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        .form-box input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-box button { width: 100%; background: #088178; color: white; padding: 12px; border: none; cursor: pointer; border-radius: 4px; font-weight: 700; }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-box">
            <img src="img/asset foto/logo6.png" alt="Logo" width="150">
            <h2>Daftar Akun</h2>
            <form action="" method="POST">
                <input type="text" name="nama" placeholder="Nama Lengkap" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="no_hp" placeholder="Nomor WhatsApp" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="daftar">Daftar Sekarang</button>
            </form>
            <p style="margin-top:15px; font-size:14px;">Sudah punya akun? <a href="login.php" style="color:#088178;">Login</a></p>
        </div>
    </div>
</body>
</html>
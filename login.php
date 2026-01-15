<?php
session_start();
include "koneksi.php";

if (isset($_POST['login'])) {
    $user_input = mysqli_real_escape_string($conn, $_POST['user_input']);
    $password = $_POST['password'];


    // Query mencari di kolom email ATAU no_hp sesuai screenshot database kamu
    $result = mysqli_query($conn, "SELECT * FROM customers WHERE email = '$user_input' OR no_hp = '$user_input'");
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verifikasi password hash
        if (password_verify($password, $row['password'])) {
            $_SESSION['id_customer'] = $row['id_customer'];
            $_SESSION['nama_customer'] = $row['nama'];
            // Simpan no_hp ke session saat login berhasil
$_SESSION['no_hp'] = $row['no_hp'];
            
            echo "<script>alert('Login Berhasil!'); window.location='index.php';</script>";
            exit;
        }
    }
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Ngelokal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container { display: flex; justify-content: center; align-items: center; height: 100vh; background: #f0f2f5; }
        .form-box { background: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        .form-box img { margin-bottom: 20px; }
        .form-box h2 { color: #088178; margin-bottom: 20px; font-family: 'Spartan', sans-serif; }
        .form-box input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .form-box button { width: 100%; background: #088178; color: white; padding: 12px; border: none; cursor: pointer; border-radius: 5px; font-weight: 700; font-size: 16px; transition: 0.3s; }
        .form-box button:hover { background: #066b63; }
        .error-msg { color: #d9534f; background: #f2dede; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 14px; }
        .form-box p { margin-top: 20px; font-size: 14px; color: #666; }
        .form-box a { color: #088178; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-box">
            <a href="index.php"><img src="img/asset foto/logo6.png" alt="Logo Ngelokal" width="160"></a>
            <h2>Login Pelanggan</h2>
            
            <?php if(isset($error)) : ?>
                <div class="error-msg">Email/No HP atau Password salah!</div>
            <?php endif; ?>

            <form action="" method="POST">
                <input type="text" name="user_input" placeholder="Email atau Nomor WhatsApp" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Masuk</button>
            </form>
            
            <p>Belum punya akun? <a href="daftar.php">Daftar Sekarang</a></p>
        </div>
    </div>
</body>
</html>
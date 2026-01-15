<?php
session_start();
include "../koneksi.php";

if (isset($_POST['simpan'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga  = $_POST['harga'];
    $stok   = $_POST['stok'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Logika Upload Gambar
    $nama_file = $_FILES['gambar']['name'];
    $source    = $_FILES['gambar']['tmp_name'];
    $folder    = '../img/produk/'; 

    // Pastikan folder ada
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    move_uploaded_file($source, $folder . $nama_file);

    // Simpan ke database
    $insert = mysqli_query($conn, "INSERT INTO produk (nama_produk, harga, stok, deskripsi, gambar) 
                                   VALUES ('$nama', '$harga', '$stok', '$deskripsi', '$nama_file')");

    if ($insert) {
        echo "<script>
                alert('Produk berhasil ditambahkan!');
                window.location.href='produk.php';
              </script>";
    } else {
        echo "<script>alert('Gagal menambah produk: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Admin Ngelokal</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Spartan:wght@300;400;500;600;700&display=swap');

        body { 
            background-color: #f0f2f5; 
            margin: 0; 
            display: flex;
            font-family: 'Spartan', sans-serif;
        }

        /* Sidebar Styling - Menyesuaikan screenshot kamu */
        #admin-aside { 
            width: 250px; 
            background: #2c3e50; 
            height: 100vh; 
            position: fixed; 
            color: white; 
            padding-top: 20px; 
        }
        #admin-aside h2 { 
            padding: 0 25px; 
            font-size: 20px; 
            margin-bottom: 30px; 
            color: white;
        }
        #admin-aside a { 
            display: block; 
            color: #bdc3c7; 
            padding: 15px 25px; 
            text-decoration: none; 
            transition: 0.3s; 
            font-size: 14px;
        }
        #admin-aside a:hover, #admin-aside a.active { 
            background: #088178; 
            color: white; 
        }
        #admin-aside a i { margin-right: 10px; width: 20px; }

        /* Main Content */
        .main-content { 
            flex: 1; 
            margin-left: 250px; 
            padding: 40px; 
            display: flex;
            justify-content: center;
        }

        /* Card Styling */
        .container-box { 
            width: 100%;
            max-width: 700px;
            background: white; 
            padding: 35px; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
        }

        .container-box h2 { 
            font-size: 22px; 
            color: #222; 
            margin-bottom: 30px;
            font-weight: 700;
        }

        /* Form Styling */
        .form-group { margin-bottom: 20px; }
        label { 
            display: block; 
            margin-bottom: 10px; 
            font-weight: 600; 
            font-size: 13px; 
            color: #444; 
        }
        input, textarea { 
            width: 100%; 
            padding: 12px 15px; 
            border: 1px solid #ddd; 
            border-radius: 10px; 
            font-family: 'Spartan', sans-serif;
            font-size: 14px;
            outline: none;
        }
        input:focus, textarea:focus { border-color: #088178; }
        textarea { height: 100px; resize: none; }

        /* Button Styling */
        .btn-submit { 
            background: #088178; 
            color: white; 
            padding: 15px; 
            border: none; 
            width: 100%; 
            border-radius: 10px; 
            font-weight: 700; 
            cursor: pointer; 
            transition: 0.3s;
            margin-top: 10px;
        }
        .btn-submit:hover { background: #06655e; transform: translateY(-2px); }
        
        .btn-back {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #888;
            text-decoration: none;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div id="admin-aside">
    <h2>Admin Ngelokal</h2>
    <a href="index.php"><i class="fas fa-th-large"></i> Dashboard</a>
    <a href="produk.php" class="active"><i class="fas fa-box"></i> Produk</a>
    <a href="pesanan.php"><i class="fas fa-shopping-cart"></i> Pesanan</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-content">
    <div class="container-box">
        <h2>Tambah Produk Baru</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama" placeholder="Contoh: Tas Anyaman Bambu" required>
            </div>
            
            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" placeholder="50000" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Stok</label>
                    <input type="number" name="stok" placeholder="10" required>
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi Produk</label>
                <textarea name="deskripsi" placeholder="Jelaskan keunggulan produk ini..."></textarea>
            </div>

            <div class="form-group">
                <label>Foto Produk</label>
                <input type="file" name="gambar" required>
            </div>

            <button type="submit" name="simpan" class="btn-submit">SIMPAN PRODUK</button>
            <a href="produk.php" class="btn-back">Batal dan Kembali</a>
        </form>
    </div>
</div>

</body>
</html>
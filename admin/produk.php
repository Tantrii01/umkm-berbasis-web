<?php
session_start();
include "../koneksi.php";

// Ambil data produk dari database
$query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Admin Ngelokal</title>
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
        }

        /* Container Tabel */
        .container-box { 
            background: white; 
            padding: 30px; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header-flex h2 { font-size: 22px; color: #222; margin: 0; }

        /* Button Tambah */
        .btn-tambah {
            background: #088178;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            transition: 0.3s;
        }
        .btn-tambah:hover { background: #06655e; transform: translateY(-2px); }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; }
        th { 
            text-align: left; 
            padding: 15px; 
            color: #888; 
            font-size: 11px; 
            text-transform: uppercase;
            border-bottom: 2px solid #f0f2f5;
        }
        td { 
            padding: 15px; 
            border-bottom: 1px solid #f0f2f5; 
            font-size: 14px; 
            color: #444;
            vertical-align: middle;
        }

        .img-produk {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        /* Badge Stok */
        .badge-stok {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }
        .stok-ada { background: #e8f5e9; color: #2e7d32; }
        .stok-habis { background: #ffebee; color: #c62828; }

        /* Action Buttons */
        .btn-action {
            padding: 8px;
            border-radius: 6px;
            text-decoration: none;
            margin-right: 5px;
            display: inline-block;
        }
        .btn-edit { background: #3498db; color: white; }
        .btn-hapus { background: #e74c3c; color: white; }
    </style>
</head>
<body>

<div id="admin-aside">
    <h2>Admin Ngelokal</h2>
    <a href="index.php"><i class="fas fa-th-large"></i> Dashboard</a>
    <a href="produk.php" class="active"><i class="fas fa-box"></i> Produk</a>
    <a href="pesan.php"><i class="fas fa-box"></i> pesan</a>
    <a href="pesanan.php"><i class="fas fa-shopping-cart"></i> Pesanan</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-content">
    <div class="container-box">
        <div class="header-flex">
            <h2>Daftar Produk</h2>
            <a href="tambah.php" class="btn-tambah"><i class="fas fa-plus"></i> Tambah Produk</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <td>
                        <img src="../img/produk/<?= $row['gambar']; ?>" class="img-produk" alt="foto">
                    </td>
                    <td style="font-weight: 600;"><?= $row['nama_produk']; ?></td>
                    <td style="color: #088178; font-weight: 700;">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?= $row['stok']; ?> unit</td>
                    <td>
                        <?php if($row['stok'] > 0) : ?>
                            <span class="badge-stok stok-ada">Tersedia</span>
                        <?php else : ?>
                            <span class="badge-stok stok-habis">Habis</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $row['id_produk']; ?>" class="btn-action btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="hapus.php?id=<?= $row['id_produk']; ?>" class="btn-action btn-hapus" title="Hapus" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
                
                <?php if(mysqli_num_rows($query) == 0) : ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: #999;">Belum ada produk yang ditambahkan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
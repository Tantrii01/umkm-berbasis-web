<?php
session_start();
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id'");
    $p = mysqli_fetch_assoc($query);

    if (!$p) {
        echo "Produk tidak ditemukan!";
        exit;
    }
} else {
    header("Location: shop.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail - <?= $p['nama_produk']; ?></title> 
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="style.css" />
    <style>
        #cartDropdown {
            position: absolute;
            top: 80px;
            right: 20px;
            background: white;
            border: 1px solid #ddd;
            padding: 20px;
            width: 350px;
            z-index: 1000;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            max-height: 85vh;
            overflow-y: auto;
            border-radius: 8px;
        }
        .co-input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-checkout-step {
            width: 100%;
            background: #088178;
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <section id="header">
        <a href="index.php"><img src="img/asset foto/logo6.png" class="logo" alt="Logo"></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                
                <?php if(isset($_SESSION['id_customer'])): ?>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="about.php">Tentang</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li id="lg-bag"><a href="cart.php"><i class="far fa-shopping-bag"></i></a></li>
                    <li><a href="tracking.php" title="Cek Pesanan"><i class="fas fa-box-alt"></i></a></li>
                    
                    <li><a href="logout.php" >Logout (<?= $_SESSION['nama_customer']; ?>)</a></li>
                <?php else: ?>
                    
                    <li><a href="daftar.php">Daftar</a></li>
                <?php endif; ?>
            

        <div id="cartDropdown" style="display: none;">
            <h4 style="margin-bottom: 15px; border-bottom: 2px solid #088178; padding-bottom: 5px;">Keranjang Belanja</h4>
            
            <div id="cartItems"></div>
            <div id="cartTotal" style="font-weight: bold; margin: 15px 0; font-size: 16px;">Total: Rp0</div>
            
            <button id="btnShowForm" class="btn-checkout-step" onclick="window.toggleFormAlamat(true)">Lanjut ke Checkout</button>

            <div id="checkoutForm" style="display: none; margin-top: 15px; border-top: 1px solid #eee; pt-10px;">
                <h5 style="margin-bottom: 10px; color: #088178;">Data Pengiriman</h5>
                <input type="text" id="co_nama" class="co_input" placeholder="Nama Lengkap">
                <input type="text" id="co_hp" class="co_input" placeholder="Nomor WhatsApp">
                <textarea id="co_alamat" class="co_input" style="height: 60px;" placeholder="Alamat Lengkap"></textarea>
                
                <select id="co_metode" class="co_input" onchange="window.tampilRekening(this.value)">
                    <option value="">Pilih Pembayaran</option>
                    <option value="COD">COD (Bayar di Tempat)</option>
                    <option value="Transfer">Transfer Bank</option>
                </select>

                <div id="infoRekening" style="display:none; background:#e8f5e9; padding:15px; font-size:12px; border-left: 4px solid #088178; margin-bottom:10px; border-radius: 4px;">
    <p style="margin:0 0 10px 0;">
        <i class="fas fa-university"></i> <strong>Pembayaran Transfer:</strong><br>
        BCA: <strong>123456789</strong><br>
        a/n Ngelokal Official
    </p>
    <a href="https://wa.me/6285175315271?text=Halo%20Admin%2C%20saya%20sudah%20melakukan%20pembayaran.%20Mohon%20dicek%20ya." 
       target="_blank" 
       style="display: inline-block; background: #25d366; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; font-weight: bold; text-align: center; width: 100%; box-sizing: border-box;">
        <i class="fab fa-whatsapp"></i> Kirim Bukti Transfer
    </a>
</div>
            

                <button id="checkoutBtn" class="btn-checkout-step" style="background: #222;">Konfirmasi Pesanan</button>
                <button onclick="window.toggleFormAlamat(false)" style="width:100%; background:none; border:none; color:red; font-size:12px; cursor:pointer; margin-top:5px;">Batal</button>
            </div>
        </div>
    </section>

    <section id="prodetails" class="section-p1">
        <div class="single-pro-image">
            <?php 
            $gambar = $p['gambar'];
            $path = 'img/produk/'.$gambar;
            echo '<img src="'.(file_exists($path) && $gambar != "" ? $path : "img/produk/default.png").'" width="100%" id="MainImg" alt="">';
            ?>
        </div>

        <div class="single-pro-details">
            <h6>ngelokal</h6>
            <h4><?= $p['nama_produk']; ?></h4>
            <h2>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></h2>
            
            <select id="productSize">
                <option value="">Pilih ukuran</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>
            </select>

            <input type="number" id="productQty" value="1" min="1">
            <button class="normal" onclick="siapkanTambah()">Tambahkan ke keranjang</button>

            <h4>Deskripsi Produk</h4>
            <span><?= nl2br($p['deskripsi']); ?></span>
        </div>
    </section>
          <footer class="section-p1">
        <div class="col">
            <img class="logo" src="img/asset foto/logo6.png" alt="" />
            <h4>Contact</h4>
            <p><strong>Alamat:</strong> Palembang</p>
            <p><strong>Phone:</strong> 085175315271</p>
            <p><strong>Hours:</strong> 10:00-18:00. Senin-Sabtu</p>
        </div>
        <div class="col">
            <h4>About</h4>
            <a href="#">About us</a>
            <a href="#">Delivery information</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms & Conditions</a>
            <a href="#">Contact Us</a>
        </div>
        <div class="col">
            <h4>My Account</h4>
            <a href="#">View Cart</a>
            <a href="#">My Wishlist</a>
            <a href="#">Track My Order</a>
            <a href="#">Help</a>
        </div>
        <div class="col create-by">
            <h4>Create By</h4>
            <p>Web Ini Dibikin oleh @Tantri</p>
        </div>
        <div class="copyright">
            <p>© Copyright ngelokal 2025. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
   window.toggleFormAlamat = function(show) {
    document.getElementById("checkoutForm").style.display = show ? "block" : "none";
    document.getElementById("btnShowForm").style.display = show ? "none" : "block";
};

// FUNGSI UTAMA: Tambah ke Keranjang
function siapkanTambah() {
    const size = document.getElementById("productSize").value;
    const qty = parseInt(document.getElementById("productQty").value);
    
    if(!size) { 
        alert("Pilih ukuran terlebih dahulu!"); 
        return; 
    }
    
    // Ambil data produk dari PHP
    const productData = {
        id: "<?= $p['id_produk']; ?>",
        name: "<?= addslashes($p['nama_produk']); ?>",
        price: <?= $p['harga']; ?>,
        img: "img/produk/<?= $p['gambar']; ?>",
        size: size,
        qty: qty
    };

    // Ambil data keranjang lama dari localStorage
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    // Cek apakah produk dengan ID dan Ukuran yang sama sudah ada
    const existingItem = cart.find(item => item.id === productData.id && item.size === productData.size);

    if (existingItem) {
        existingItem.qty += qty;
    } else {
        cart.push(productData);
    }

    // Simpan kembali ke localStorage
    localStorage.setItem("cart", JSON.stringify(cart));

    alert("Berhasil menambahkan " + productData.name + " ke keranjang!");
    
    // Opsional: Langsung arahkan ke halaman cart.php
    window.location.href = "cart.php";
}
</script>

<script src="script.js"></script>
</body>
</html>
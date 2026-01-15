<?php
session_start();
include "koneksi.php";

// Query dibatasi cuma 4 produk terbaru
$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ngelokal</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <section id="header">
        <a href="index.php"><img src="img/asset foto/logo6.png" class="logo" alt="Logo"></a>
        
        <div>
            <ul id="navbar">
                <li><a class="active" href="index.php">Home</a></li>
                
                <?php if(isset($_SESSION['id_customer'])): ?>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li id="lg-bag"><a href="cart.php"><i class="far fa-shopping-bag"></i></a></li>
                    <li><a href="tracking.php" title="Cek Pesanan"><i class="fas fa-box-alt"></i></a></li>
                    
                    <li><a href="logout.php" style="color: #e63946;">Logout (<?= $_SESSION['nama_customer']; ?>)</a></li>
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

            

    <section id="hero">
        <h4>Pakai Lokal, Rasa Internasional</h4>
        <h2>Ngelokal, stylish</h2>
        <h1>Dengan Sentuhan Tradisi</h1>
        <p>Ngelokal menghadirkan koleksi fashion modern yang terinspirasi dari budaya batik Indonesia.<br>Nyaman dipakai sehari-hari, tetap elegan di setiap kesempatan</p>
        <button onclick="window.location.href='shop.php';">Shop Now</button>
    </section>

    <section id="product1" class="section-p1">
        <h2>Produk Unggulan</h2>
        <p>Koleksi Kami</p>
        <div class="pro-container">
            <?php while($p = mysqli_fetch_assoc($produk)): 
                $nama_tampil = $p['nama_produk'] ?? $p['nama'];
                $id_p = $p['id_produk'];
            ?>
            <div class="pro" onclick="window.location.href='detail.php?id=<?= $id_p; ?>';">
                <?php if($p['gambar'] != '' && file_exists('img/produk/'.$p['gambar'])): ?>
                    <img src="img/produk/<?= $p['gambar']; ?>" alt="<?= $nama_tampil; ?>" />
                <?php else: ?>
                    <img src="img/produk/default.png" alt="No Image" />
                <?php endif; ?>

                <div class="des">
                    <span><?= $p['jenis'] ?? 'Fashion'; ?></span>
                    <h5><?= $nama_tampil; ?></h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></h4>
                </div>
                <a href="detail.php?id=<?= $id_p; ?>" class="cart"><i class="fal fa-shopping-cart"></i></a>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
    
    <section id="banner" class="section-m1">
        <h4>Dari Nusantara Untuk Dunia</h4>
        <h2>Tradisi Lama, <span>Style</span> Masa Kini</h2>
        <button class="normal" onclick="window.location.href='shop.php';">Explore More</button>
    </section>

    <section id="sm-banner" class="section-p1">
        <div class="banner-box">
            <h4>Segera Hadir</h4>
            <h2>Summer Collection</h2>
            <span>Koleksi terbaru ngelokal untuk musim panas ini</span>
            <button class="white">Learn More</button>
        </div>
        <div class="banner-box bg2">
            <h4>New drop</h4>
            <h2>upcoming season</h2>
            <span>saatnya tampil segar & trendy <br>dengan koleksi summer terbaru dari Ngelokal</span>
            <button class="white">Collection</button>
        </div>
    </section>

    <section id="banner3">
        <div class="banner-box">
            <h2>SEASONAL SALE</h2>
        </div>
        <div class="banner-box bb2">
            <h2>NEW BRAND COLLECTION</h2>
        </div>
        <div class="banner-box bb3">
            <h2>T-Shirts</h2>
        </div>
    </section>

    <footer class="section-p1">
        <div class="col">
            <img class="logo" src="img/asset foto/logo6.png" alt="Logo" />
            <h4>Contact</h4>
            <p><strong>Alamat:</strong> Palembang</p>
            <p><strong>Phone:</strong> 085175315271</p>
            <p><strong>Hours:</strong> 10:00-18:00. Senin-Sabtu</p>
            <div class="follow">
                <h4>Follow us</h4>
                <div class="icon">
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-youtube"></i>
                    <i class="fab fa-twitter"></i>
                </div>
            </div>
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

    <script src="script.js"></script>
</body>
</html>
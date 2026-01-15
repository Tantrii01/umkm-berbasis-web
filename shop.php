<?php
session_start();
include "koneksi.php";

// Ambil semua produk dari database
$query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shop - Ngelokal</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="style.css" />

    <style>
        /* pagination */
        #pagination { text-align: center; padding: 40px 0; }
        #pagination a {
            text-decoration: none;
            background-color: #088178;
            padding: 15px 20px;
            border-radius: 4px;
            color: #fff;
            font-weight: 600;
            margin: 0 5px;
        }
        #pagination a.active {
            background-color: #055e58;
            border: 2px solid #fff;
        }
       
        /* Cart Dropdown */
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
        <a href="index.php"><img src="img/asset_foto/logo6.png" class="logo" alt="Logo"></a>
        
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                
                <?php if(isset($_SESSION['id_customer'])): ?>
                    <li><a class="active" href="shop.php">Shop</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li id="lg-bag"><a href="cart.php"><i class="far fa-shopping-bag"></i></a></li>
                    <li><a href="tracking.php" title="Cek Pesanan"><i class="fas fa-box-alt"></i></a></li>
                    <li><a href="logout.php" style="color: #e63946;">Logout (<?= $_SESSION['nama_customer']; ?>)</a></li>
                <?php else: ?>
                    
                    <li><a href="daftar.php">Daftar</a></li>
                <?php endif; ?>

                <a href="#" id="close"><i class="far fa-times"></i></a>
            </ul>
        </div>

        

        <div id="cartDropdown" style="display: none;">
            <h4 style="margin-bottom: 15px; border-bottom: 2px solid #088178; padding-bottom: 5px;">Keranjang Belanja</h4>
            <div id="cartItems"></div>
            <div id="cartTotal" style="font-weight: bold; margin: 15px 0; font-size: 16px;">Total: Rp0</div>
            
            <button id="btnShowForm" class="btn-checkout-step" onclick="window.toggleFormAlamat(true)">Lanjut ke Checkout</button>

            <div id="checkoutForm" style="display: none; margin-top: 15px; border-top: 1px solid #eee; padding-top: 10px;">
                <h5 style="margin-bottom: 10px; color: #088178;">Data Pengiriman</h5>
                <input type="text" id="co_nama" class="co-input" placeholder="Nama Lengkap">
                <input type="text" id="co_hp" class="co-input" placeholder="Nomor WhatsApp">
                <textarea id="co_alamat" class="co-input" style="height: 60px;" placeholder="Alamat Lengkap"></textarea>
                
                <select id="co_metode" class="co-input" onchange="window.tampilRekening(this.value)">
                    <option value="">Pilih Pembayaran</option>
                    <option value="COD">COD (Bayar di Tempat)</option>
                    <option value="Transfer">Transfer Bank</option>
                </select>

                <div id="infoRekening" style="display:none; background:#e8f5e9; padding:10px; font-size:12px; border-left: 4px solid #088178; margin-bottom:10px;">
                    <p style="margin:0;">BCA: <strong>123456789</strong><br>a/n Ngelokal Official</p>
                    <a href="https://wa.me/6285175315271?text=Halo%20Admin%2C%20saya%20mau%20konfirmasi." target="_blank" style="color:green; text-decoration:none; font-weight:bold;">Konfirmasi WA</a>
                </div>

                <button id="checkoutBtn" class="btn-checkout-step" style="background: #222;">Konfirmasi Pesanan</button>
                <button onclick="window.toggleFormAlamat(false)" style="width:100%; background:none; border:none; color:red; font-size:12px; cursor:pointer; margin-top:5px;">Batal</button>
            </div>
        </div>
    </section>

    <section id="page-header">
        <h2>#NgelokalStyle</h2>
        <p>Temukan Koleksi fashion lokal pilihan dengan sentuhan modern.</p>
    </section>

    <section id="product1" class="section-p1">
        <div class="pro-container">
            <?php 
            while($p = mysqli_fetch_assoc($query)): 
                $nama_produk = $p['nama_produk'] ?? $p['nama'];
                $id_p = $p['id_produk'];
            ?>
            <div class="pro" onclick="window.location.href='detail.php?id=<?= $id_p; ?>';">
                <?php if($p['gambar'] != '' && file_exists('img/produk/'.$p['gambar'])): ?>
                    <img src="img/produk/<?= $p['gambar']; ?>" alt="<?= $nama_produk; ?>" />
                <?php else: ?>
                    <img src="img/produk/default.png" alt="No Image" />
                <?php endif; ?>

                <div class="des">
                    <span><?= $p['jenis'] ?? 'Koleksi Lokal'; ?></span>
                    <h5><?= $nama_produk; ?></h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></h4>
                </div>
                <a href="detail.php?id=<?= $id_p; ?>"><i class="fal fa-shopping-cart cart"></i></a>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section id="pagination" class="section-p1"></section>

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
    </footer> <script src="cart.js"></script>
    <script src="script.js"></script>

    <script>
        // Fungsi pembantu untuk form alamat (Checkout)
        window.toggleFormAlamat = function(show) {
            const form = document.getElementById("checkoutForm");
            const btn = document.getElementById("btnShowForm");
            if(form) form.style.display = show ? "block" : "none";
            if(btn) btn.style.display = show ? "none" : "block";
        };

        window.tampilRekening = function(val) {
            const info = document.getElementById("infoRekening");
            if (info) info.style.display = (val === "Transfer") ? "block" : "none";
        };

        // Logika Pagination Otomatis
        const products = document.querySelectorAll(".pro");
        const productsPerPage = 12; 
        const totalPages = Math.ceil(products.length / productsPerPage);
        let currentPage = 1;

        function showPage(page) {
            products.forEach((p, i) => {
                p.style.display =
                    i >= (page - 1) * productsPerPage && i < page * productsPerPage
                    ? "block" : "none";
            });
            renderPagination(page);
        }

        function renderPagination(page) {
            const pagination = document.getElementById("pagination");
            pagination.innerHTML = "";
            if (totalPages <= 1) return;

            for (let i = 1; i <= totalPages; i++) {
                const link = document.createElement("a");
                link.href = "#";
                link.textContent = i;
                if (i === page) link.classList.add("active");
                link.addEventListener("click", (e) => {
                    e.preventDefault();
                    currentPage = i;
                    showPage(currentPage);
                    window.scrollTo(0, 500); 
                });
                pagination.appendChild(link);
            }
        }
        showPage(currentPage);
    </script>
</body>
</html>
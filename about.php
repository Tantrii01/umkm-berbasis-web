<?php
// WAJIB di baris paling pertama!
session_start(); 
include "koneksi.php";

// Cek apakah user sudah login, jika belum arahkan ke login.php atau beri nama 'Guest'
if (!isset($_SESSION['nama_customer'])) {
    // Pilihan A: Paksa login
    // header("Location: login.php"); exit;

    // Pilihan B: Beri nama default agar tidak error offset null
    $nama_tampil = "Pelanggan"; 
} else {
    $nama_tampil = $_SESSION['nama_customer'];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IT Fest Kelompok</title>
    <link
      rel="stylesheet"
      href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    />

    <link rel="stylesheet" href="style.css" />
     <style>
        /* CSS untuk Keranjang agar konsisten */
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
            display: none; /* Default sembunyi */
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
      <a href="#"><img src="img/asset foto/logo6.png" class="logo" alt="" ></a>

      <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a class="active" href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li id="lg-bag"><a href="cart.php"><i class="far fa-shopping-bag"></i></a></li>
                <li><a href="tracking.php" title="Cek Pesanan"><i class="fas fa-box-alt"></i></a></li>
                
                <li><a href="logout.php" style="color: #e63946;">Logout (<?= $_SESSION['nama_customer']; ?>)</a></li>
            </ul>
      <div id="cartDropdown">
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

        <div id="mobile">
            <a href="#"><i class="far fa-shopping-bag"></i></a>
            <i id="bar" class="fas fa-outdent"></i> 
        </div>
    </section>

    <section id="page-header" class="about-header">
      
      <h2>#KnowUs</h2>
      <p>Temukan bagaimana Ngelokal menghadirkan produk berkualitas <br> dengan sentuhan lokal yang penuh makna </p>
    </section>
    
    <section  id="about-head" class="section-p1">
      <img src="img/about/a6.jpg" alt="" />
      <div>
        <h2>Who We Are?</h2>
        <p>
          Ngelokal adalah brand fashion yang lahir dari semangat untuk mengangkat karya anak bangsa. Kami menghadirkan produk yang memadukan kenyamanan, kualitas, dan identitas lokal agar tetap relevan di era modern.
        </p>
        <abbr title="Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.">
          <strong>Our Mission</strong>
        </abbr>
        <p>
          Misi kami adalah menjadikan produk lokal sebagai pilihan utama dengan menghadirkan fashion berkualitas, berkarakter, dan mudah dijangkau. Kami percaya setiap karya lokal memiliki daya saing untuk tampil di panggung nasional maupun internasional.
        </p>
        <abbr title="Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.">
          <strong>Our Vision</strong>
        </abbr>
        <p>
          Visi kami adalah menciptakan ekosistem fashion lokal yang berkelanjutan, di mana setiap orang bangga memakai karya negeri sendiri. Ngelokal hadir untuk membawa identitas Indonesia dalam gaya hidup modern.
        </p>
      </div>
    </section>

    <footer class="section-p1">
      <div class="col">
        <img class="logo" src="img/asset foto/logo6.png" alt="" />
        <h4>Contact</h4>
        <p><strong>Alamat:</strong> palembang</p>
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
        <a href="#">Contac Us</a>
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
        <p>Web Ini Dibikin </p>
        
       <p>@Tantri</p>
       
        

      </div>

      <div class="copyright">
       <p>© Copyright ngelokal 2025. All rights reserved.</p>
      </div>




    </footer>
<script src="cart.js"></script>
    <script src="script.js"></script>
    <script>
    // Fungsi untuk memunculkan/menyembunyikan form alamat saat tombol diklik
    window.toggleFormAlamat = function(show) {
        const form = document.getElementById("checkoutForm");
        const btn = document.getElementById("btnShowForm");
        if (form) form.style.display = show ? "block" : "none";
        if (btn) btn.style.display = show ? "none" : "block";
    };

    // Fungsi untuk menampilkan info rekening hanya jika metode "Transfer" dipilih
    window.tampilRekening = function(val) {
        const info = document.getElementById("infoRekening");
        if (info) info.style.display = (val === "Transfer") ? "block" : "none";
    };
</script>
  </body>
</html>

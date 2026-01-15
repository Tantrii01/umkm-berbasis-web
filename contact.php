
<?php
// 1. WAJIB di baris paling pertama untuk membaca login
session_start(); 

// 2. Memanggil koneksi database
include "koneksi.php";

// 3. Logika untuk menampilkan nama agar tidak error jika belum login
// Sesuaikan 'nama_customer' dengan nama session saat login kamu
$nama_user = isset($_SESSION['nama_customer']) ? $_SESSION['nama_customer'] : "Guest";

// 4. Logika untuk menyimpan pesan (Create)
if (isset($_POST['submit_pesan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);

    $query = mysqli_query($conn, "INSERT INTO pesan (nama, email, subject, pesan) 
                                  VALUES ('$nama', '$email', '$subject', '$pesan')");

    if ($query) {
        echo "<script>
                alert('Pesan Anda telah berhasil dikirim!');
                window.location.href='contact.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Maaf, pesan gagal dikirim: " . mysqli_error($conn) . "');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us - Ngelokal</title>
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
            display: none;
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
                <li><a href="shop.php">Shop</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a  href="about.php">About</a></li>
                <li><a class="active" href="contact.php">Contact</a></li>
                <li id="lg-bag"><a href="cart.php"><i class="far fa-shopping-bag"></i></a></li>
                <li><a href="tracking.php" title="Cek Pesanan"><i class="fas fa-box-alt"></i></a></li>
                
                <li><a href="logout.php" style="color: #e63946;">Logout (<?= $_SESSION['nama_customer']; ?>)</a></li>
            </ul>
        </div>

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
                    <a href="https://wa.me/6285175315271" target="_blank" style="color:green; text-decoration:none; font-weight:bold;">Konfirmasi WA</a>
                </div>

                <button id="checkoutBtn" class="btn-checkout-step" style="background: #222;">Konfirmasi Pesanan</button>
                <button onclick="window.toggleFormAlamat(false)" style="width:100%; background:none; border:none; color:red; font-size:12px; cursor:pointer; margin-top:5px;">Batal</button>
            </div>
        </div>
    </section>

    <section id="page-header" class="about-header">
        <h2>#let's-talk</h2>
        <p>TINGGALKAN PESAN. Kami senang mendengar kabar Anda!</p>
    </section>

    <section id="contact-details" class="section-p1">
        <div class="details">
            <span>GET IN TOUCH</span>
            <h2>Hubungi kami hari ini</h2>
            <h3>Kantor Pusat</h3>
            <div>
                <li><i class="fal fa-map"></i><p>Palembang</p></li>
                <li><i class="far fa-envelope"></i><p>contact@ngelokal.com</p></li>
                <li><i class="fas fa-phone-alt"></i><p>085175315271</p></li>
                <li><i class="far fa-clock"></i><p>Senin - Sabtu: 09.00 - 16.00 WIB</p></li>
            </div>
        </div>
       <div class="map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127504.42617631327!2d104.6811100418579!3d-2.958933256038472!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e3b75e8f2393e9d%3A0x3039d80ec054690!2sPalembang%2C%20Kota%20Palembang%2C%20Sumatera%20Selatan!5e0!3m2!1sid!2sid!4v1715600000000!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
    </section>

    <section id="form-details">
        <form action="" method="POST">
            <span>Kirimkan Sebuah Pesan</span>
            <h2>Kami senang mendengar kabar dari Anda</h2>
            <input type="text" name="nama" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="text" name="subject" placeholder="Subject">
            <textarea name="pesan" cols="30" rows="10" placeholder="Your Message" required></textarea>
            <button type="submit" name="submit_pesan" class="normal">Submit</button>
        </form>
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

    <script src="cart.js"></script>
    <script src="script.js"></script>
    <script>
        window.toggleFormAlamat = function(show) {
            const form = document.getElementById("checkoutForm");
            const btn = document.getElementById("btnShowForm");
            if (form) form.style.display = show ? "block" : "none";
            if (btn) btn.style.display = show ? "none" : "block";
        };

        window.tampilRekening = function(val) {
            const info = document.getElementById("infoRekening");
            if (info) info.style.display = (val === "Transfer") ? "block" : "none";
        };
    </script>
</body>
</html>
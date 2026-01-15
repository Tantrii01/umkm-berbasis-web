<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_customer'])) {
    echo "<script>alert('Silahkan login!'); window.location='login.php';</script>";
    exit;
}

$id_cust = $_SESSION['id_customer'];
$query_cust = mysqli_query($conn, "SELECT * FROM customers WHERE id_customer = '$id_cust'");
$data_cust = mysqli_fetch_assoc($query_cust);

// Variabel cadangan agar tidak muncul error "Undefined array key"
$nama_tampil   = $data_cust['nama_customer'] ?? $data_cust['nama'] ?? "";
$hp_tampil     = $data_cust['no_hp'] ?? $data_cust['telepon'] ?? "";
$alamat_tampil = $data_cust['alamat'] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Keranjang - Ngelokal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <style>
        #cart { padding: 40px 80px; }
        #cart table { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: fixed; }
        #cart table thead { border: 1px solid #e2e9e1; border-left: none; border-right: none; }
        #cart table thead td { font-weight: 700; text-transform: uppercase; font-size: 13px; padding: 18px 0; text-align: center; }
        #cart table tbody td { padding-top: 15px; text-align: center; border-bottom: 1px solid #eee; }
        #cart table img { width: 70px; }
        .checkout-form { background: #f0f4f7; padding: 30px; border-radius: 8px; margin-top: 20px; max-width: 500px; }
        .checkout-form input, .checkout-form select, .checkout-form textarea { 
            width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #088178; border-radius: 5px; box-sizing: border-box; 
        }
        .label-form { font-weight: bold; font-size: 14px; display: block; margin-top: 10px; color: #222; }
        #checkoutBtn {
            background:#088178; color:white; padding:15px; border:none; cursor:pointer; 
            width:100%; font-weight:bold; font-size:16px; margin-top:10px; border-radius:5px; transition: 0.3s;
        }
        #checkoutBtn:hover { background: #06655e; }
    </style>
</head>
<body>
    <section id="header">
        <a href="index.php"><img src="img/asset foto/logo6.png" class="logo"></a>
        <ul id="navbar">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a class="active" href="cart.php"><i class="far fa-shopping-bag"></i></a></li>
            <li><a href="tracking.php"><i class="fas fa-box-alt"></i></a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </section>

    <section id="cart" class="section-p1">
        <table>
            <thead>
                <tr>
                    <td width="100">Remove</td>
                    <td width="100">Image</td>
                    <td>Product</td>
                    <td width="80">Size</td>
                    <td width="120">Price</td>
                    <td width="80">Qty</td>
                    <td width="120">Subtotal</td>
                </tr>
            </thead>
            <tbody id="cartTableBody"></tbody>
        </table>

        <form action="proses_checkout.php" method="POST" id="formCheckout">
            <div class="checkout-form">
                <h3>Total Pesanan: <span id="displayTotal" style="color: #088178;">Rp 0</span></h3>
                
                <input type="hidden" name="cart_data" id="cartDataInput">

                <label class="label-form">Nama Penerima:</label>
                <input type="text" name="nama" id="co_nama" value="<?= $nama_tampil; ?>" required>

                <label class="label-form">No HP / WhatsApp:</label> 
                <input type="text" name="no_hp" id="co_hp" value="<?= $hp_tampil; ?>" required> 

                <label class="label-form">Alamat Lengkap Pengiriman:</label>
                <textarea name="alamat" id="co_alamat" rows="4" required><?= $alamat_tampil; ?></textarea>
                
                <label class="label-form">Metode Pembayaran:</label>
                <select name="metode" id="co_metode" onchange="cekMetode()" required>
                    <option value="">-- Pilih Pembayaran --</option>
                    <option value="Transfer Bank">Transfer Bank (BCA)</option>
                    <option value="COD">COD (Payar di Tempat)</option>
                </select>

                <div id="infoRekening" style="display:none; background:#e8f5e9; padding:15px; font-size:13px; border-left: 5px solid #088178; margin: 15px 0; border-radius: 5px;">
                    <p>Silahkan transfer ke rekening berikut:</p>
                    <p>BCA: <strong>123456789</strong><br>a/n Ngelokal Official</p>
                    <hr>
                    <p>Kirim bukti transfer ke WhatsApp admin.</p>
                </div>

                <button type="submit" name="proses_checkout" id="checkoutBtn">KONFIRMASI & PESAN SEKARANG</button>
            </div>
        </form>
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

    <script>
        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        function cekMetode() {
            const metode = document.getElementById("co_metode").value;
            const infoBox = document.getElementById("infoRekening");
            infoBox.style.display = (metode === "Transfer Bank") ? "block" : "none";
        }

        function renderCart() {
            const table = document.getElementById("cartTableBody");
            const totalEl = document.getElementById("displayTotal");
            table.innerHTML = "";
            let total = 0;

            if (cart.length === 0) {
                table.innerHTML = "<tr><td colspan='7'><h3>Keranjang kosong.</h3></td></tr>";
                return;
            }

            cart.forEach((item, index) => {
                if (item.name && item.name !== "undefined") {
                    let price = Number(item.price) || Number(item.harga) || 0;
                    let qty = Number(item.qty) || 1;
                    let sub = price * qty;
                    total += sub;

                    table.innerHTML += `
                        <tr>
                            <td><i class="far fa-times-circle" onclick="removeItem(${index})" style="cursor:pointer;color:red;"></i></td>
                            <td><img src="${item.img || 'img/no-image.png'}"></td>
                            <td><strong>${item.name}</strong></td>
                            <td>${item.size || '-'}</td>
                            <td>Rp ${price.toLocaleString('id-ID')}</td>
                            <td>${qty}</td>
                            <td><strong>Rp ${sub.toLocaleString('id-ID')}</strong></td>
                        </tr>`;
                }
            });
            totalEl.innerText = "Rp " + total.toLocaleString('id-ID');
        }

        window.removeItem = (index) => {
            if(confirm("Hapus item ini?")) {
                cart.splice(index, 1);
                localStorage.setItem("cart", JSON.stringify(cart));
                renderCart();
            }
        };

        // PERBAIKAN UTAMA: Penanganan Submit Form
        document.getElementById("formCheckout").addEventListener("submit", function(e) {
            if (cart.length === 0) {
                alert("Keranjang kosong!");
                e.preventDefault();
                return;
            }
            
            // Simpan data keranjang ke input hidden sebelum dikirim
            document.getElementById("cartDataInput").value = JSON.stringify(cart);
            
            // Ubah teks tombol saja, JANGAN pakai .disabled = true di sini agar tombol tetap terbaca PHP
            document.getElementById("checkoutBtn").innerText = "Sedang Memproses...";
        });

        renderCart();
    </script>
</body>
</html>
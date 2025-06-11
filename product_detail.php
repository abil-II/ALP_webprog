<!-- filepath: /Applications/XAMPP/xamppfiles/htdocs/ALP_WEBPROG/product_detail.php -->
<?php
include 'connection.php';

// Check if the product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Product ID is missing.');
}

$conn = my_connectDB();
$product_id = intval($_GET['id']);

// Fetch product details from the database
$query = "SELECT p.*, k.nama_kategori 
          FROM produk p 
          LEFT JOIN kategori k ON p.kategori_id = k.id 
          WHERE p.id = $product_id AND p.stok > 0";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    die('Product not found.');
}

$product = mysqli_fetch_assoc($result);

// Jika stok habis, redirect ke produk.php atau tampilkan pesan stok habis
if ($product['stok'] <= 0) {
    // Redirect ke halaman produk, atau bisa juga tampilkan pesan stok habis
    header('Location: produk.php?stok=habis');
    exit();
}

// Ambil jumlah produk ini di keranjang jika sudah ada (dari tabel keranjang, bukan session)
$qty_in_cart = 1;
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
    $user_id = $_SESSION['id'];
    $conn2 = my_connectDB();
    $q = mysqli_query($conn2, "SELECT jumlah FROM keranjang WHERE user_id=$user_id AND produk_id=$product_id");
    if ($row = mysqli_fetch_assoc($q)) {
        $qty_in_cart = (int)$row['jumlah'];
    }
    my_closeDB($conn2);
}

my_closeDB($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['nama_produk']) ?> - StepIn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-200">

    <nav class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 fixed top-0 left-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-4 h-14 flex items-center justify-between">
            <h1 class="text-xl font-bold text-blue-600 tracking-tight">StepIn</h1>
            <ul id="mainNavLinks" class="flex space-x-8 text-sm font-medium hidden md:flex ml-auto"> <!-- Hide on small screens, show on md+; ml-auto to push right -->
                <li><a href="index.php" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Beranda</a></li>
                <li><a href="produk.php" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Produk</a></li>
                <li><a href="kategori.php" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Kategori</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="read.php" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Entry Management</a></li>
                <?php endif; ?>
                
                <li><a href="akun.php" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Akun</a></li>
                <li><a href="logout.php" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Logout</a></li>
            </ul>
            <div style="position: relative; min-width: 40px;">
                <button id="toggleLogout" type="button" class="md:hidden"> <!-- Show on small screens only -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                    </svg>
                </button>

                <!-- Mobile Nav Dropdown -->
                <div id="mobileNavLinks" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg z-50">
                    <a href="logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Logout</a>
                    <a href="akun.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Akun</a>
                    <a href="index.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Beranda</a>
                    <a href="produk.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Produk</a>
                    <a href="kategori.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Kategori</a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="read.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Entry Management</a>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                        <a href="keranjang.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Entry Management</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <section class="max-w-7xl mx-auto px-4 py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Product Image -->
            <div>
                <img src="<?= htmlspecialchars($product['gambar']) ?>" alt="<?= htmlspecialchars($product['nama_produk']) ?>" class="w-full h-auto rounded-lg shadow-md">
            </div>

            <!-- Product Details -->
            <div>
                <h2 class="text-3xl font-bold mb-4"><?= htmlspecialchars($product['nama_produk']) ?></h2>
                <p class="text-gray-600 dark:text-gray-400 mb-4"><?= htmlspecialchars($product['deskripsi']) ?></p>
                <p class="text-lg font-semibold text-blue-600 mb-4">Rp <?= number_format($product['harga'], 0, ',', '.') ?></p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Kategori: <?= htmlspecialchars($product['nama_kategori']) ?></p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Stok: <?= htmlspecialchars($product['stok']) ?></p>

                <!-- Add to Cart Button with Modal Quantity Picker -->
                <button id="showQtyModal" type="button" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    beli
                </button>
                <!-- Modal -->
                <div id="qtyModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-xs relative">
                        <button id="closeQtyModal" type="button" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
                        <h3 class="text-lg font-bold mb-4 text-center">Pilih Jumlah</h3>
                        <form id="qtyForm" class="flex flex-col gap-4">
                            <input type="hidden" name="add" value="<?= $product['id'] ?>">
                            <label for="qty" class="font-medium">Jumlah:</label>
                            <input type="number" name="qty" id="qty" value="<?= $qty_in_cart ?>" min="1" max="<?= $product['stok'] ?>" class="w-full px-2 py-1 border rounded" required>
                            <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tambahkan ke Keranjang</button>
                        </form>
                        <div id="cartMsg" class="mt-4 text-center text-green-600 font-semibold hidden">Berhasil ditambahkan ke keranjang!</div>
                    </div>
                </div>
                <script>
                    document.getElementById('showQtyModal').onclick = function() {
                        document.getElementById('qtyModal').classList.remove('hidden');
                        document.getElementById('cartMsg').classList.add('hidden');
                    };
                    document.getElementById('closeQtyModal').onclick = function() {
                        document.getElementById('qtyModal').classList.add('hidden');
                    };
                    document.getElementById('qtyModal').addEventListener('click', function(e) {
                        if (e.target === this) this.classList.add('hidden');
                    });

                    // AJAX untuk update jumlah di keranjang
                    const qtyForm = document.getElementById('qtyForm');
                    qtyForm.onsubmit = function(e) {
                        e.preventDefault();
                        const formData = new FormData(qtyForm);
                        const params = new URLSearchParams(formData).toString();
                        fetch('keranjang.php?' + params, {
                                method: 'GET',
                                credentials: 'same-origin'
                            })
                            .then(res => res.ok ? res.text() : Promise.reject(res))
                            .then(() => {
                                document.getElementById('cartMsg').classList.remove('hidden');
                                // Update input value sesuai jumlah yang baru dimasukkan
                                // (tidak perlu reload, karena session sudah update di backend)
                            })
                            .catch(() => {
                                document.getElementById('cartMsg').classList.remove('hidden');
                                document.getElementById('cartMsg').textContent = 'Gagal menambah ke keranjang!';
                                document.getElementById('cartMsg').classList.remove('text-green-600');
                                document.getElementById('cartMsg').classList.add('text-red-600');
                            });
                    };
                </script>
            </div>
        </div>
    </section>

    <footer class="bg-white shadow-inner py-6 mt-10 dark:bg-gray-800">
        <div class="text-center text-sm text-gray-500 dark:text-gray-400">
            &copy; 2025 StepIn. All rights reserved.
        </div>
    </footer>
     <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
    <!-- Floating Cart Button -->
    <a href="keranjang.php" title="Keranjang" class="fixed bottom-6 right-6 z-50 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg p-4 flex items-center justify-center transition-all duration-300" style="box-shadow: 0 4px 24px rgba(0,0,0,0.2);">
      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-cart-plus" viewBox="0 0 16 16">
        <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z" />
        <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
      </svg>
    </a>
  <?php endif; ?>

</body>

</html>